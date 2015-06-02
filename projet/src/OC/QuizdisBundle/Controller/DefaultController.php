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
		
		$statReponse= new ReponseQuestion();
		for ($i=0;$i<4;$i++) {
			$form[$i] = $this->createForm(new PlayType( array('rep'=>chr(65+$i))));
		
			if ($form[$i]->handleRequest($request)->isValid()) {
				$statReponse->setGamepin($id);
				$statReponse->setUser(8);
				$statReponse->setReponseDonnee(chr(65+$i));
				
				$em->persist($statReponse);
				$em->flush();
				if (null != $quiz->getQCMs()->get($q+1))
					return $this->redirect($this->generateUrl('oc_quizdis_play', array('id' => $id, 'q' => $q+1)));
				else
					return $this->redirect($this->generateUrl('oc_quizdis_select'));
			}
		
		}

		return $this->render('OCQuizdisBundle:Default:play.html.twig', array(
			'quiz'  => $quiz,
			'q'	=> $q,
			'reponse'	=> $statReponse,
			'form' => array (
				$form[0]->createView(),
				$form[1]->createView(),
				$form[2]->createView(),
				$form[3]->createView()
			)
		));
	}
}
