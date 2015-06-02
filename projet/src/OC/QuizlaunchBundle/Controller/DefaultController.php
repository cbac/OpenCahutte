<?php
// controller prof

namespace OC\QuizlaunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Entity\QCM;

use OC\QuizdisBundle\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class DefaultController extends Controller
{
    public function indexAction(){
      $limit=1000;
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

		return $this->render('OCQuizlaunchBundle:Default:index.html.twig', array(
		  'listQuizs' => $listQuizs
		));
    
    }

    public function pickAction($id){
      $em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
	
		
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
	
	$name = $quiz->getNom();
      $gamepin =  rand ( 1 , 999999 ); 
	
	
    /* affichage utilisateur */ 
		
		return $this->render('OCQuizlaunchBundle:Default:pick.html.twig', array(
			'id'  => $id,
			'gamepin' => $gamepin,
			'name' => $name
		));
	
    
    }

    public function launchquestionAction($id,$gamepin,$q){
    
      
    
             
      $em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
 	
      $QCM=$quiz->getQCMs()->get($q);
		if (null === $QCM) {
			throw new NotFoundHttpException("La question d'id ".$q." n'existe pas.");
		}
		
	$texte = $q->getQuestion();	
      
      if (null != $quiz->getQCMs()->get($q) ){

       // timer
      $hfin = time()+20; // on pose le temps de réponse à une question de 20s
      
      $timer = new Timer; 
      $timer->setHfin($hFin);
      $timer->setGamepin($gamepin);
      $timer->setQuizId($quizid);
      $timer->setQuestion($q);
	
      // afficher horloge
      $date = 20;
	
      return $this->render('OCQuizlaunchBundle:Default:launch.html.twig', array('quiz' => $quiz, 'q' => $q, 'gamepin' => $gamepin, 'texte' => $texte)	);
      }
      else {
      return $this->render('OCQuizlaunchBundle:Default:index.html.twig');
      
      }
      
    }
    
     
    
    public function showresultsAction($gamepin){
    
   
    
    }
    
}
