<?php

namespace OC\QuizdisBundle\Controller;

use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Form\QuizType;
use OC\QuizdisBundle\Entity\ReponseQuestion;

use OC\QuizdisBundle\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
	{	

		$largeurChamps = array('style'=> 'width: 400px');

		$form = $this->createFormBuilder()
			->add('gamepin', 'integer', array(
				'label' => ' Entrer le Gamepin', 
				'attr' => $largeurChamps
			))
			->add('save', 'submit', array(
				'label' => 'GO !', 
				'attr' => $largeurChamps
			))
			->getForm();

		$form->handleRequest($request);
		
		$data = $form->getData();

		if ($form->isValid()) {
			// Les données sont un tableau avec la clé gamepin
			
			
			// si le quiz d'id gamepin n'existe pas, erreur
			$em = $this->getDoctrine()->getManager();
			$quiz = $em
				->getRepository('OCQuizgenBundle:Quiz')
				->find($data['gamepin'])
			;
			
			
			if (null === $quiz) {
				throw new NotFoundHttpException("Le quiz d'id ".$data['gamepin']." n'existe pas.");
			}
		
		
			$request->getSession()->getFlashBag()->add('notice', 'Lancement du quiz');
			return $this->redirect($this->generateUrl('oc_quizgen_view', array('id' => $quiz->getId())));
		}
		
		return $this->render('OCQuizdisBundle:Default:index.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
    public function playAction($id,$q,Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		
		$QCM=$quiz->getQCMs()->get($q);
		if (null === $QCM) {
			throw new NotFoundHttpException("La question d'id ".$q." n'existe pas.");
		}
		
		$statReponse= new ReponseQuestion();
		$form = $this->createForm(new PlayType( array('reponses'=>$QCM->getReponsesPossibles()) ), $statReponse);
		
		if ($form->handleRequest($request)->isValid()) {
		
			if( $statReponse->getReponse() === $QCM->getReponsesJustes() )
				$statReponse->setJuste(true);
			else
				$statReponse->setJuste(false);
			
			$statReponse->setTemps(0);
			$em->persist($statReponse);
			$em->flush();
			if (null != $quiz->getQCMs()->get($q+1))
				return $this->redirect($this->generateUrl('oc_quizdis_play', array('id' => $id, 'q' => $q+1)));
			else
				return $this->redirect($this->generateUrl('oc_quizdis_select'));
		}

		return $this->render('OCQuizdisBundle:Default:play.html.twig', array(
			'quiz'  => $quiz,
			'q'	=> $q,
			'reponse'	=> $statReponse,
			'form' => $form->createView()
		));
	}
}
