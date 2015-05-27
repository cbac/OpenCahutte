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
		
		$reponse= new ReponseQuestion();
		$form = $this->createForm(new PlayType(), $reponse);
		
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		if ($form->handleRequest($request)->isValid()) {
			$em->persist($reponse);
			
			$em->flush();

			return $this->redirect($this->generateUrl('oc_quizdis_play', array('id' => $id, 'q' => $q+1)));
		}

		return $this->render('OCQuizdisBundle:Default:play.html.twig', array(
			'quiz'  => $quiz,
			'quest'	=> $q,
			'reponse'	=> $reponse
		));
	}
}
