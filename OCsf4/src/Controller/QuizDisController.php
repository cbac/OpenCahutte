<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Entity\ReponseQuestion;
use App\Entity\PointQuestion;
use App\Entity\Gamepin;

use App\Form\GamepinType;
use App\Form\PseudoType;
use App\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class QuizDisController extends Controller
{
    public function indexAction(Request $request)
	{	

		$largeurChamps = 'width: 200px';

		$gamepin = new Gamepin();
		
		$form = $this->createForm(new GamepinType(), $gamepin);
		


		$form->handleRequest($request);
		


		if ($form->isValid()) {
		
			return $this->redirect($this->generateUrl('oc_quizdis_pseudo', array('gamepin' => $gamepin->getGamepin())));
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
		
		if ( $idAuteur == 0 ) {
		
		  $auteur = "anonyme"; 
		
		}
		
		else {
		  $userManager = $this->get('fos_user.user_manager');
		  $auteur = $userManager->findUserBy(array('id' => $idAuteur))->getUsername();
		}
		
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
