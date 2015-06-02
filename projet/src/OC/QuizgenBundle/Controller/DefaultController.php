<?php

namespace OC\QuizgenBundle\Controller;


use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Form\QuizType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
	
	public function addAction(Request $request)
	{
	
		// On crée un objet Quiz
		$quiz = new Quiz();
		

		
		// On crée le FormBuilder grâce au service form factory
		$form = $this->createForm(new QuizType(), $quiz, array('user' => $this->getUser()));
		
		
		// À partir de maintenant, la variable $quiz contient les valeurs entrées dans le formulaire par le visiteur
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($quiz);
			
			foreach ($quiz->getQCMs() as $QCM) {
				$QCM->setQuiz($quiz);
			}
			
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Vous avez créé un quiz avec succès.');

			return $this->redirect($this->generateUrl('oc_quizgen_view', array('id' => $quiz->getId())));
		}
			 
		// On passe la méthode createView() du formulaire à la vue
		// afin qu'elle puisse afficher le formulaire toute seule
		return $this->render('OCQuizgenBundle:Default:add.html.twig', array(
			'form' => $form->createView(),
		));
	} 
	
	public function editAction($id, Request $request)
	{
	
		$em = $this->getDoctrine()->getManager();
		
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		$form = $this->createForm(new QuizType(), $quiz);
		
		if ($form->handleRequest($request)->isValid()) {
			foreach ($quiz->getQCMs() as $QCM) {
				$QCM->setQuiz($quiz);
			}
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Vous avez modifié un quiz avec succès.');
			return $this->redirect($this->generateUrl('oc_quizgen_view', array('id' => $quiz->getId())));
		}
		return $this->render('OCQuizgenBundle:Default:edit.html.twig', array(
			'form' => $form->createView(),
			'quiz' => $quiz
		));
	} 
  
	public function viewAction($id)
	{
		 
		$em = $this->getDoctrine()->getManager();
		// On récupère le $id
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}

		return $this->render('OCQuizgenBundle:Default:view.html.twig', array(
			'quiz'           => $quiz
		));
	}
	
	public function menuAction($limit)
	{
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

		return $this->render('OCQuizgenBundle:Default:menu.html.twig', array(
		  'listQuizs' => $listQuizs
		));
	}
	
	public function deleteAction(Quiz $quiz)
	{
		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression d'article contre cette faille
		$form = $this->createFormBuilder()->getForm();

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) { // Ici, isValid ne vérifie donc que le CSRF
				// On supprime l'article
				$em = $this->getDoctrine()->getManager();
				$em->remove($quiz);
				$em->flush();

				// On définit un message flash
				$this->get('session')->getFlashBag()->add('info', 'Le quiz a été supprimé.');

				// Puis on redirige vers l'accueil
				return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
			}
		}
		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render('OCQuizgenBundle:Default:delete.html.twig', array(
		  'quiz' => $quiz,
		  'form'    => $form->createView()
		));
	}
	
	
	public function duplicateAction($id, Request $request)
	{	
		$nQuiz = new Quiz();
		
		$em = $this->getDoctrine()->getManager();
		//aQuiz pour ancien quiz
		$aQuiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		if (null === $aQuiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
		
		//$nQuiz->setQuiz(aQuiz->getQuiz());
		$nQuiz = clone $aQuiz;
		
		$form = $this->createForm(new QuizType(), $nQuiz);
		
		if ($form->handleRequest($request)->isValid()) {
			$em->persist($nQuiz);
			
			foreach ($nQuiz->getQCMs() as $QCM) {
				$QCM->setQuiz($nQuiz);
			}
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Vous avez dupliqué un quiz avec succès.');
			return $this->redirect($this->generateUrl('oc_quizgen_view', array('id' => $nQuiz->getId())));
		}
		return $this->render('OCQuizgenBundle:Default:duplicate.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
}
