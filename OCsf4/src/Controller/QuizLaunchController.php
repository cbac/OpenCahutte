<?php
// controller prof
namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QCM;
use App\Entity\ReponseQuestion;
use App\Entity\Timer;
use App\Entity\Gamepin;
use App\Entity\PointQuestion;
use App\Entity\Stats;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @Route("/launch")
 */
class QuizLaunchController extends AbstractController
{

    /**
     * Lists all quizs
     *
     * @Route("/", name="oc_launch_index", methods={"GET"})
     */
    public function indexAction()
    {
        $limit = 1000;
        $listQuizs = $this->getDoctrine()
            ->getManager()
            ->getRepository(Quiz::class)
            ->findBy(array(), array(
            'id' => 'desc'
        ), $limit, 0);

        return $this->render('OCQuizlaunch\index.html.twig', array(
            'listQuizs' => $listQuizs
        ));
    }

    /**
     * Pick a quiz
     *
     * @Route("/{id}", name="oc_launch_pick", requirements={
     * "id": "\d+" }, methods={"GET"})
     */
    public function pickAction(Request $request, Quiz $quiz)
    {
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }

        $name = $quiz->getNom();

        /* gamepin ajouté dans la BD pour permettre la connexion des joueurs */
        /**
         * TODO check uniqueness of gamepin *
         */
        $gamepin = new Gamepin($quiz);
        

        $session = $request->getSession();
        $session->set('creatorGamepin', $gamepin->getPinNumber());

        $timer = new Timer();
        $timer->setGamepin($gamepin);
        $timer->setQNumber(0);
        $timer->setHfin(0);
        $timer->setHdebut(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($timer);
        $em->persist($gamepin);
        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Début du quiz.');
        }

        /* affichage utilisateurs */

        return $this->render('OCQuizlaunch\pick.html.twig', array(
            'gamepin' => $gamepin,
            'name' => $name
        ));
    }

    /**
     * Launch one question:
     *
     * @Route("/question/{gamepin}/{idq}", name="oc_launch_question", requirements={
     * "gamepin": "\d+", "idq": "\d+" }, methods={"GET"})
     */
    public function launchquestionAction(Request $request, Gamepin $gamepin, $idq)
    {
        $session = $request->getSession();
        if ($session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin->getPinNumber()) {
            // On récupère l'EntityManager
            $em = $this->getDoctrine()->getManager();
            $quiz = $gamepin->getQuiz();
            if($quiz == null) {
                throw new NotFoundHttpException("Launchquestion can't get quiz with gamepin ". $gamepin->getPinNumber());
            }
            $nbqTot = $quiz->getNbQuestions();
            if ($idq <= $nbqTot) {
                $question = $quiz->getQCMs()->get($idq-1);
                if ($question == Null) {
                    throw new NotFoundHttpException("Launchquestion can't get question in quiz ");
                }
                
                $texte = $question->getQuestion();
                $tempsrep = $question->getTemps();

                // timer
                $hdebut = time();
                $hfin = $hdebut + $tempsrep; // on pose le temps de réponse à une question de 20s

                $timer = new Timer();
                $timer->setHdebut($hdebut);
                $timer->setHfin($hfin);
                $timer->setGamepin($gamepin);
                $timer->setQNumber($idq);

                // Étape 1 : On « persiste » le timer
                $em->persist($timer);

                // Étape 2 : On « flush » tout ce qui a été persisté avant
                $em->flush();

 //               if ($request->isMethod('POST')) {
                    $request->getSession()
                        ->getFlashBag()
                        ->add('notice', 'Début de la question.');
   //             }

                // si idq == nbquestion

                return $this->render('OCQuizlaunch\launch.html.twig', array(
                    'idq' => $idq,
                    'gamepin' => $gamepin,
                    'texte' => $texte,
                    'question' => $question,
                    'tempsrep' => $tempsrep
                ));
            } 
            else /* if( idq > nbqTot) */
            {
                return $this->redirect($this->generateUrl('oc_launch_final', 
                    array(
                    'gamepin' => $gamepin->getId()
                    )
                    ));
            }
        } 
        else
            return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
    }

    /**
     * Launch one question:
     *
     * @Route("/score/{gamepin}/{idq}", name="oc_launch_score", requirements={
     * "gamepin": "\d+", "idq": "\d+" }, methods={"GET"})
     */
    public function resQuestionAction(Request $request, Gamepin $gamepin, $idq)
    {
        /** TODO check algorithm and unused vars **/
        $session = $request->getSession();
        if ($session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin->getPinNumber()) {

            $em = $this->getDoctrine()->getManager();



            $QCMs = $gamepin->getQuiz()->getQCMs();

            $qcm = $QCMs->get($idq - 1);
            $reponsesQuestionsTimers = $em->getRepository(ReponseQuestion::class)
            ->findBy([ 'gamepin'=>$gamepin, 'qcm' => $qcm]);
            
            $qcm0 = $em->getRepository(PointQuestion::class)->findBy(array(
                'gamepin' => $gamepin,
                'idq' => $idq
            ));

            $allPlayers = array();

            foreach ($qcm0 as $ligne) {
                $allPlayers[] = $ligne->getPseudoJoueur();
            }

            $i = 0;
            $pseudos = array();
            $pointQuestion = array();

            foreach ($reponsesQuestionsTimers as $reponseQuestionTimer) {

                $pseudos[$i] = $reponseQuestionTimer->getUser();

                $pointQuestion[$i] = new PointQuestion();
                $em->persist($pointQuestion[$i]);
                $pointQuestion[$i]->setGamepin($gamepin);
                $pointQuestion[$i]->setPseudojoueur($reponseQuestionTimer->getUser());
                $pointQuestion[$i]->setIdq($idq);

                $reponseDonnee = $reponseQuestionTimer->getReponseDonnee();

                $reponseJuste = (($reponseDonnee == 'A' && $qcm->getJuste1()) 
                    || ($reponseDonnee == 'B' && $qcm->getJuste2()) 
                    || ($reponseDonnee == 'C' && $qcm->getJuste3()) 
                    || ($reponseDonnee == 'D' && $qcm->getJuste4())) ;

                if ($reponseJuste) {
                    $hfin = $reponseQuestionTimer->getTimer()->getHfin();
                    $hdebut = $reponseQuestionTimer->getTimer()->getHdebut();
                    $time = $reponseQuestionTimer->getTime();
                    $tempsDeReponse = $time - $hdebut;
                    $score = 500 - 400 * $tempsDeReponse / ($hfin - $hdebut);

                    $pointQuestion[$i]->setPointqx($score);
                } else
                    $pointQuestion[$i]->setPointqx(0);

                $em->flush($pointQuestion[$i]);
                $i ++;
            }

            foreach ($allPlayers as $player) {
                if (! in_array($player, $pseudos)) {
                    $pointQuestion[$i] = new PointQuestion();
                    $em->persist($pointQuestion[$i]);
                    $pointQuestion[$i]->setGamepin($gamepin);
                    $pointQuestion[$i]->setPseudojoueur($player);
                    $pointQuestion[$i]->setIdq($idq);
                    $pointQuestion[$i]->setPointqx(0);
                    $em->flush($pointQuestion[$i]);
                    $i ++;
                }
            }

            return $this->render('OCQuizlaunch\tempresult.html.twig', array(
                'idq' => $idq,
                'gamepin' => $gamepin,
                'pointQuestion' => $pointQuestion,
                'reponsesJustes' => $qcm->getReponsesJustes()
            ));
        } else {
            return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
        }
    }

    /**
     * Stats after launch
     *
     * @Route("/stats/{gamepin}", name="oc_launch_final", requirements={
     * "gamepin": "\d+" }, methods={"GET"})
     */
    public function finalAction(Request $request, Gamepin $gamepin)
    {
        /** TODO check the algorithm and code **/
        $em = $this->getDoctrine()->getManager();

        $pointsQs = new PointQuestion();

 //        $stats = new Stats();

        $repository = $em->getRepository(PointQuestion::class);

        // récupérer toutes les sessions associées au gamepin

        $pointsQs = $repository->getPointQuestionByGamepin($gamepin->getPinNumber());

        // initialiser tableau
        // recupérer tous les joueurs associés au gamepin
        $allPlayers = array();
        $pointsTot = array();

        $pointQuestion0 = $repository->findBy(array(
            'gamepin' => $gamepin,
            'idq' => 0
        ));
        
        $nbJoueurs = 0;
        foreach ($pointQuestion0 as $ligne) {
            $pseudo = $ligne->getPseudoJoueur();
            $allPlayers[] = $pseudo;
            $pointsTot[$pseudo] = 0;
            $nbJoueurs ++;
        }

        // for each joueur in sessionsrecup set stats.joueur.pttotaux = somme(points du joueur à chaque q)

        foreach ($pointsQs as $pointsQ) {

            $pointsTot[$pointsQ->getPseudojoueur()] += $pointsQ->getPointqx();
        }

        return $this->render('OCQuizlaunch\stats.html.twig', array(
            'pointsTot' => $pointsTot,
            'allPlayers' => $allPlayers,
            'gamepin' => $gamepin
        ));
    }
}
