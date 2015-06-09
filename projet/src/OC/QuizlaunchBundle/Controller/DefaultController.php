<?php
// controller prof

namespace OC\QuizlaunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Entity\QCM;
use OC\QuizlaunchBundle\Entity\Timer;

//use OC\QuizdisBundle\Form\PlayType;

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

    public function pickAction($id, Request $request){
      $em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
		
		if (null === $quiz) {
			throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
		}
	
	$name = $quiz->getNom();
	
	
	
	// Récupérer l'id de l'utilisateur qui a lancé la session de quiz
	$userManager = $this->get('fos_user.user_manager'); 
	$utilisateur = $this->container->get('security.context')->getToken()->getUser();
	
	$idcreateur = $utilisateur->getId(); 
	
		
			
      /* gamepin ajouté dans la BD pour permettre la connexion des joueurs */
      $gamepin =  rand ( 1 , 999999 ); 
      $timer = new Timer;
      $timer->setGamepin($gamepin);
      $timer->setQuizId($id);
      $timer->setQuestion(0);
      $timer->setHfin(0);
      $timer->setHdebut(0);
      $timer->setIdcreateur($idcreateur);

      $em = $this->getDoctrine()->getManager();
      $em->persist($timer);
      $em->flush();
      
      if ($request->isMethod('POST')) {
	$request->getSession()->getFlashBag()->add('notice', 'Début du quiz.');
	}
	
      
    /* affichage utilisateurs */ 
		
		return $this->render('OCQuizlaunchBundle:Default:pick.html.twig', array(
			'id'  => $id,
			'gamepin' => $gamepin,
			'name' => $name,
			'idcreateur' => $idcreateur
		));
	
    
    }

    public function launchquestionAction($id,$gamepin,$idq, $idcreateur, Request $request){
    
      
    
             
		$em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
 	
 	
 	
		$repository = $this
		->getDoctrine()
		->getManager()
		->getRepository('OCQuizgenBundle:QCM')
		  ;
      
		$question = $repository->getQbyIdq($idq,$id); dump($question);
      
 	
		 /* $QCM=$quiz->getQCMs()->get($q);
			if (null === $QCM) {
				throw new NotFoundHttpException("La question d'id ".$q." n'existe pas.");
			}
			*/
		
		$nbqTot = $quiz->getNbQuestions();
		
      	if ( $idq <= $nbqTot ) {

			//if (null !=  $repository->getQbyIdq($idq,$id)){

				
				$texte = $question[0]->getQuestion(); dump($texte);
				
				   // timer
				  $hdebut = time();
				  $hfin = $hdebut +20; // on pose le temps de réponse à une question de 20s
				  
				  $timer = new Timer;
				  $timer->setHdebut($hdebut);
				  $timer->setHfin($hfin);
				  $timer->setGamepin($gamepin);
				  $timer->setQuizId($id);
				  $timer->setQuestion($idq);
				  $timer->setIdcreateur($idcreateur);
				  
				// On récupère l'EntityManager
				  $em = $this->getDoctrine()->getManager();

				  // Étape 1 : On « persiste » l'entité
				  $em->persist($timer);

				  // Étape 2 : On « flush » tout ce qui a été persisté avant
				  $em->flush();

				  if ($request->isMethod('POST')) {
					$request->getSession()->getFlashBag()->add('notice', 'Début de la question.');
				  }
				  
				  // si idq == nbquestion
				  
				  // afficher horloge
				  $date = 20;
					
				  return $this->render('OCQuizlaunchBundle:Default:launch.html.twig', array(
				  'quiz' => $quiz,
				  'idq' => $idq,
				  'gamepin' => $gamepin,
				  'id' => $id,
				  'texte' => $texte,
				  'question' => $question[0],
				  'idcreateur' => $idcreateur
				  ));
			}  
      /*else {
      return $this->render('OCQuizlaunchBundle:Default:index.html.twig');
      
      }*/
		  
		else /*( $idq == $nbqTot+1 )*/ {
		
		  return $this->render('OCQuizlaunchBundle:Default:stats.html.twig', array(
		    'gamepin' => $gamepin,
		    'quiz' => $quiz,
		    'idcreateur' => $idcreateur
		    )); 
		  
		}
	}
      
     
    
    public function showresultsAction($gamepin){
    
   
    
    }
    
     
    public function showfinalAction($gamepin,$quiz,$idcreateur){
    
    $session = new Session(); 
    
    $repository = $this
		->getDoctrine()
		->getManager()
		->getRepository('OCQuizlaunchBundle:Session')
		  ;
      
    $session = $repository->getQbyIdq($gamepin); 
    dump($session);
    
    
    
    
    }
    
}
