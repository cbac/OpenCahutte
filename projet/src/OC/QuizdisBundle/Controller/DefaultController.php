<?php

namespace OC\QuizdisBundle\Controller;

use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Form\QuizType;
use OC\QuizdisBundle\Entity\ReponseQuestion;
use OC\QuizlaunchBundle\Entity\PointQuestion;

use OC\QuizdisBundle\Form\PseudoType;
use OC\QuizdisBundle\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
	{	

		$largeurChamps = 'width: 200px';

		$form = $this->createFormBuilder()
			->add('gamepin', 'integer', array(
				'attr' => array(
					'style'=> $largeurChamps,
				)
			))
			->add('save', 'submit', array(
				'label' => 'GO !', 
				'attr' => array(
					'style'=> $largeurChamps,
					'class'=> 'btn btn-primary'
				)
			))
			->getForm();

		$form->handleRequest($request);
		
		$data = $form->getData();

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$quiz = $em
				->getRepository('OCQuizlaunchBundle:Timer')
				->findByGamepin($data['gamepin'])
			;
			if (null === $quiz) {
				throw new NotFoundHttpException("Le quiz d'id ".$data['gamepin']." n'existe pas.");
			}
			
			return $this->redirect($this->generateUrl('oc_quizdis_pseudo', array('gamepin' => $data['gamepin'])));
		}
		
		return $this->render('OCQuizdisBundle:Default:index.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	public function pseudoAction(Request $request, $gamepin)
	{
		$pointQuestion = new PointQuestion();
		
		$form = $this->createForm(new PseudoType(), $pointQuestion);
		
		if($request->isMethod('POST')) {
			$pointQuestion->setQuizid(0);
			$pointQuestion->setIdq(0);
			$pointQuestion->setGamepin($gamepin);
			$pointQuestion->setPointqx(0);
			
			$form->handleRequest($request);
			
			if ($form->isValid()) {
				$session = $request->getSession();
				$session->set('pseudo', $pointQuestion->getPseudojoueur());
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($pointQuestion);
				$em->flush();
				
				return $this->redirect($this->generateUrl('oc_quizdis_play', array('gamepin' => $gamepin)));
			}
		}
		return $this->render('OCQuizdisBundle:Default:pseudo.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
    public function playAction($gamepin,Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$timers = $em
			->getRepository('OCQuizlaunchBundle:Timer')
			->findByGamepin($gamepin)
		;
		if (null == $timers) {
			throw new NotFoundHttpException("Le gamepin ".$gamepin." n'existe pas.");
		}
		$id=$timers[0]->getQuizid();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		$idAuteur=$quiz->getAuthor();
		$userManager = $this->get('fos_user.user_manager');
		$auteur = $userManager->findUserBy(array('id' => $idAuteur))->getUsername();
		
		$statReponse= new ReponseQuestion();
		$class=array("btn btn-primary","btn btn-success","btn btn-warning","btn btn-danger");
		for ($i=0;$i<4;$i++) {
			$form[$i] = $this->createForm(new PlayType(), $statReponse, array('rep' => chr(65+$i), 'class' => $class[$i]));
		}
		if($request->isMethod('POST')) {
			$rep=$request->get('oc_quizdisbundle_play')['reponseDonnee'];
			$idRep=ord($rep) - 65;
			
			$statReponse->setGamepin($gamepin);
			$session = $request->getSession();
			$statReponse->setUser($session->get('pseudo'));
			
			$form[$idRep]->handleRequest($request);
			
			if ($form[$idRep]->isValid()) {
				$em->persist($statReponse);
				$em->flush();
				return $this->redirect($this->generateUrl('oc_quizdis_play', array('gamepin' => $gamepin)));
			}
		}
		return $this->render('OCQuizdisBundle:Default:play.html.twig', array(
			'quiz'  => $quiz,
			'reponse'	=> $statReponse,
			'auteur' => $auteur,
			'form' => array (
				$form[0]->createView(),
				$form[1]->createView(),
				$form[2]->createView(),
				$form[3]->createView()
			)
		));
	}
}
