<?php

namespace OC\QuizdisBundle\Controller;

use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizdisBundle\Entity\ReponseQuestion;

use OC\QuizdisBundle\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
	{	
		$limit=5;
		$listQuizs = $this
			->getDoctrine()
			->getManager()
			->getRepository('OCQuizgenBundle:Quiz')
			->findBy(
				array(),
				array('id' => 'desc'),
				$limit,
				0
			)
		;

		return $this->render('OCQuizdisBundle:Default:index.html.twig', array(
		  'listQuizs' => $listQuizs
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
		$reponses=array(
			$QCM->getRep1(),
			$QCM->getRep2(),
			$QCM->getRep3(),
			$QCM->getRep4()
		);
		
		$statReponse= new ReponseQuestion();
		$form = $this->createForm(new PlayType( array('reponses'=>$reponses) ), $statReponse);
		
		if ($form->handleRequest($request)->isValid()) {
			$em->persist($statReponse);
			
			$em->flush();

			return $this->redirect($this->generateUrl('oc_quizdis_play', array('id' => $id, 'q' => $q+1)));
		}

		return $this->render('OCQuizdisBundle:Default:play.html.twig', array(
			'quiz'  => $quiz,
			'q'	=> $q,
			'reponse'	=> $statReponse,
			'form' => $form->createView()
		));
	}
}
