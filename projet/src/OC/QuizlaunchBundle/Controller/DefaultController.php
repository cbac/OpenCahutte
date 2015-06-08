<?php
// controller prof

namespace OC\QuizlaunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Entity\QCM;
use OC\QuizdisBundle\Entity\ReponseQuestion;
use OC\QuizlaunchBundle\Entity\Timer;
use OC\QuizlaunchBundle\Entity\Session;

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
		
			
      /* gamepin ajouté dans la BD pour permettre la connexion des joueurs */
      $gamepin =  rand ( 1 , 999999 ); 
      $timer = new Timer;
      $timer->setGamepin($gamepin);
      $timer->setQuizId($id);
      $timer->setQuestion(0);
      $timer->setHfin(0);
      $timer->setHdebut(0);

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
			'name' => $name
		));
	
    
    }

    public function launchquestionAction($id,$gamepin,$idq, Request $request){
    
      
    
             
		$em = $this->getDoctrine()->getManager();
		$quiz = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
		;
 	
 	
 	
		$question = $this
			->getDoctrine()
			->getManager()
			->getRepository('OCQuizgenBundle:QCM')
			->getQbyIdq($idq,$id)
		;
      
 	
		 /* $QCM=$quiz->getQCMs()->get($q);
			if (null === $QCM) {
				throw new NotFoundHttpException("La question d'id ".$q." n'existe pas.");
			}
			*/
		
		$nbqTot = $quiz->getNbQuestions();
		
      	if ( $idq <= $nbqTot ) {

			//if (null !=  $repository->getQbyIdq($idq,$id)){

				
				$texte = $question[0]->getQuestion();
				
				   // timer
				  $hdebut = time();
				  $hfin = $hdebut +20; // on pose le temps de réponse à une question de 20s
				  
				  $timer = new Timer;
				  $timer->setHdebut($hdebut);
				  $timer->setHfin($hfin);
				  $timer->setGamepin($gamepin);
				  $timer->setQuizId($id);
				  $timer->setQuestion($idq);
				  
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
				  ));
			}  
      /*else {
      return $this->render('OCQuizlaunchBundle:Default:index.html.twig');
      
      }*/
		  
		else /*( $idq == $nbqTot+1 )*/ {
		
		  return $this->render('OCQuizlaunchBundle:Default:stats.html.twig'); 
		  
		}
	}
	
	
	
	
	public function resQuestionAction($id, $idq, $gamepin, Request $request) {
		
		$em = $this->getDoctrine()->getManager();
		
		$reponsesQuestionsTimers = $this
			->getDoctrine()
			->getManager()
			->getRepository('OCQuizdisBundle:ReponseQuestion')
			->getReponseQuestionTimer($gamepin, $idq)
		;		
		
		$QCMs = $em
			->getRepository('OCQuizgenBundle:Quiz')
			->find($id)
			->getQCMs()
		;
		
		$qcm = $QCMs
			->get($idq-1)
		;
			

			
		dump($QCMs);
		
		
		
		$i = 0;
		
		foreach ($reponsesQuestionsTimers as $reponseQuestionTimer) {
			
			$pseudos[$i] = $reponseQuestionTimer->getUser();
			
			$session[$i] = new Session();
			$em->persist($session[$i]);
			$session[$i]->setGamepin($reponseQuestionTimer->getGamepin());
			$session[$i]->setQuizid($reponseQuestionTimer->getTimer()->getQuizid());
			$session[$i]->setIdcreateur(0);
			$session[$i]->setPseudojoueur($reponseQuestionTimer->getUser());
			$session[$i]->setIdq($reponseQuestionTimer->getTimer()->getQuestion());
		
			$reponseDonnee = $reponseQuestionTimer->getReponseDonnee();

			
			$reponseJuste = false;
			if(($reponseDonnee == 'A' && $qcm->getJuste1())
					|| ($reponseDonnee == 'B' && $qcm->getJuste2())
					|| ($reponseDonnee == 'C' && $qcm->getJuste3())
					|| ($reponseDonnee == 'D' && $qcm->getJuste4())
			){
				$reponseJuste = true;
			}
			
			if($reponseJuste) {
				$hfin = $reponseQuestionTimer->getTimer()->getHfin();
				$hdebut = $reponseQuestionTimer->getTimer()->getHdebut();
				$time = $reponseQuestionTimer->getTime();
				$tempsDeReponse = $time - $hdebut;
				$score = 500 - 400*$tempsDeReponse/($hfin - $hdebut);
				
				$session[$i]->setPointqx($score);
			} else
				$session[$i]->setPointqx(0);
			dump($session);
			
			$em->flush($session[$i]);
			$i++;
			
		}
		

	return $this->render('OCQuizlaunchBundle:Default:tempresult.html.twig', array(
			'id'  => $id,
			'idq' => $idq,
			'gamepin' => $gamepin,
			'session' => $session
		));
	
		
		
	}
      
     
    
    public function showresultsAction($gamepin){
    
   
    
    }
    
}
