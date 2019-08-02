<?php
// controller prof

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Quiz;
use App\Entity\QCM;
use App\Entity\ReponseQuestion;
use App\Entity\Timer;
use App\Entity\PointQuestion;
use App\Entity\Stats;

//use OC\QuizdisBundle\Form\PlayType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class QuizLaunchController extends Controller
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

	  $session = $request->getSession();
	  $session->set('creatorGamepin', $gamepin);
	  
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
    
		$session = $request->getSession();
		if( $session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin ) {
		
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
					$tempsrep = $question[0]->getTemps();
					
					   // timer
					  $hdebut = time();
					  $hfin = $hdebut + $tempsrep; // on pose le temps de réponse à une question de 20s
					  
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
					  
					 						
					  return $this->render('OCQuizlaunchBundle:Default:launch.html.twig', array(
					  'quiz' => $quiz,
					  'idq' => $idq,
					  'gamepin' => $gamepin,
					  'id' => $id,
					  'texte' => $texte,
					  'question' => $question[0],
					  'tempsrep' => $tempsrep
					  ));
				}  
		  
			
		  else /*if( idq > nbqTot)*/ {
		return $this->redirect($this->generateUrl('oc_quizlaunch_stats', array(
					  'gamepin' => $gamepin,
					  'id' => $id,
					  'quiz' => $quiz)));
					 
		      
		  }
		}
		
		else
			return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
	}
	
	
	
	
	public function resQuestionAction($id, $idq, $gamepin, Request $request) {
		$session = $request->getSession();
		if( $session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin ) {
		
			$em = $this->getDoctrine()->getManager();
			
			$reponsesQuestionsTimers = $em
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


			$qcm0 = $em
				->getRepository('OCQuizlaunchBundle:PointQuestion')
				->findBy(array('gamepin'=>$gamepin, 'idq'=>0))
			;		
			
			$quiz = $em
				->getRepository('OCQuizgenBundle:Quiz')
				->find($id)
			;
			$nbqTot = $quiz->getNbQuestions();

				
			
			$nbJoueurs = 0;
			$allPlayers = array();
			
			foreach ($qcm0 as $ligne) {
				$allPlayers[$nbJoueurs] = $qcm0[$nbJoueurs]->getPseudoJoueur();
				$nbJoueurs ++;
			}

			
			$i = 0;
			$pseudos = array();
			$pointQuestion= array();
			
			foreach ($reponsesQuestionsTimers as $reponseQuestionTimer) {
				
				$pseudos[$i] = $reponseQuestionTimer->getUser();
				
				$pointQuestion[$i] = new PointQuestion();
				$em->persist($pointQuestion[$i]);
				$pointQuestion[$i]->setGamepin($reponseQuestionTimer->getGamepin());
				$pointQuestion[$i]->setQuizid($reponseQuestionTimer->getTimer()->getQuizid());
				$pointQuestion[$i]->setPseudojoueur($reponseQuestionTimer->getUser());
				$pointQuestion[$i]->setIdq($reponseQuestionTimer->getTimer()->getQuestion());
			
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
					
					$pointQuestion[$i]->setPointqx($score);
				} else
					$pointQuestion[$i]->setPointqx(0);
				
				$em->flush($pointQuestion[$i]);
				$i++;
				
			}
			
			foreach($allPlayers as $player) {
				if (!in_array($player, $pseudos)) {
					$pointQuestion[$i] = new PointQuestion();
					$em->persist($pointQuestion[$i]);
					$pointQuestion[$i]->setGamepin($gamepin);
					$pointQuestion[$i]->setQuizid($id);
					$pointQuestion[$i]->setPseudojoueur($player);
					$pointQuestion[$i]->setIdq($idq);	
					$pointQuestion[$i]->setPointqx(0);
					$em->flush($pointQuestion[$i]);
					$i++;
				}
			}

		

			return $this->render('OCQuizlaunchBundle:Default:tempresult.html.twig', array(
					'id'  => $id,
					'idq' => $idq,
					'gamepin' => $gamepin,
					'pointQuestion' => $pointQuestion,
					'reponsesJustes' => $qcm->getReponsesJustes()
				));
				
				
			
				  
	
		}
		else{		
		      return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
		}
		
	    
	}
        
    public function showfinalAction($gamepin,$id){
    
      $em = $this->getDoctrine()->getManager();
      
      $quiz = $em
				->getRepository('OCQuizgenBundle:Quiz')
				->find($id)
			;      
      
      $pointsQs = new PointQuestion(); 
      
      $stats = new Stats(); 
      
      $repository = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('OCQuizlaunchBundle:PointQuestion')
		    ;
	
       // récupérer toutes les sessions associées au gamepin

      $pointsQs = $repository->getPointQuestionByGamepin($gamepin); 
      
      // initialiser tableau 
      // recupérer tous les joueurs associés au gamepin 
      $nbJoueurs = 0;
      $allPlayers = array(); 
      $pointsTot = array();
      
      $pointQuestion0 = $em
			->getRepository('OCQuizlaunchBundle:PointQuestion')
			->findBy(array('gamepin'=>$gamepin, 'idq'=>0))
		;
      
      
      foreach ($pointQuestion0 as $ligne) {
	  
	$allPlayers[$nbJoueurs] = $pointQuestion0[$nbJoueurs]->getPseudoJoueur();
	$pointsTot[$pointQuestion0[$nbJoueurs]->getPseudoJoueur()] =0;
	$nbJoueurs ++;
	
      }

     
      // for each joueur in sessionsrecup set stats.joueur.pttotaux = somme(points du joueur à chaque q)
      
      foreach ( $pointsQs as $pointsQ ){
      
	$pointsTot[$pointsQ->getPseudojoueur()]+= $pointsQ->getPointqx();
	      
      }
      
      
      return $this->render('OCQuizlaunchBundle:Default:stats.html.twig', array(
			'pointsTot' => $pointsTot,
			'allPlayers' => $allPlayers,
			'quiz' => $quiz
			 ));
    
    }

     /*
    
     
    public function showfinalAction($gamepin){
    
		$session = $request->getSession();
		if( $session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin ) {
			  //$idquiz = 1; 
			  
			  $pointsQs = new PointQuestion(); 
			  
			  $stats = new Stats(); 
			  
			  $repository = $this
				  ->getDoctrine()
				  ->getManager()
				  ->getRepository('OCQuizlaunchBundle:PointQuestion')
					;
			
			   // récupérer toutes les sessions associées au gamepin

			  $pointsQs = $repository->getPointQuestionByGamepin($gamepin); 

			  
			  // initialiser tableau 
			  
			  $allPlayers;
			  $pointsTot = 100; 
			  
			  
			  // for each joueur in sessionsrecup set stats.joueur.pttotaux = somme(points du joueur à chaque q)
			  
			  foreach ( $pointsQs as $pointsQ ){
			  
			$pointsTot[$pointsQ->getPseudojoueur()]+= $pointsQ->getPointqx();
				  
			  }
			  
			  
			  
			  return $this->render('OCQuizlaunchBundle:Default:stats.html.twig', array(
					'pointsTot' => $pointsTot,
					'allPlayers' => $allPlayers
					 ));
			
		}
		else
			return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
    
	}*/
}
