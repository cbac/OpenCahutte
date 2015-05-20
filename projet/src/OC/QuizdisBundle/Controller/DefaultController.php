<?php

namespace OC\QuizdisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
	{	
		$listQuizs = $this
			->getDoctrine()
			->getManager()
			->getRepository('OCQuizgenBundle:Quiz')
			->findAll()
		;

		return $this->render('OCQuizgenBundle:Default:index.html.twig', array(
		  'listQuizs' => $listQuizs
		));
	}
	
    public function playAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		//$form = $this->createForm(new , $);
	}
}
