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
use Symfony\Component\HttpFoundation\Response;
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
     * Pick a pinNumber
     *
     * @Route("/{id}", name="oc_launch_gamepin", requirements={
     * "id": "\d+" }, methods={"GET"})
     */
    public function gamepinAction(Request $request, Quiz $quiz)
    {
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }

        $name = $quiz->getNom();
        $gamepin = null;
        /* create new Gamepin and check uniqueness of pinNumber */
        while (true) {
            $gamepin = new Gamepin($quiz);
            if (self::checkPinNumber($gamepin->getPinNumber()))
                break;
        }

        $session = $request->getSession();
        $session->set('creatorGamepin', $gamepin->getPinNumber());

        $timer = new Timer();
        $timer->setGamepin($gamepin);
        $timer->setQNumber(0);
        $timer->setHfin(0);
        $timer->setHdebut(0);
        /* gamepin and timer added to database */

        $em = $this->getDoctrine()->getManager();
        $em->persist($timer);
        $em->persist($gamepin);
        $em->flush();

        /* Display PinNumber and Waits for players */

        return $this->render('OCQuizlaunch\gamepin.html.twig', array(
            'gamepin' => $gamepin,
            'name' => $name
        ));
    }

    /**
     * check if pinNumber is ok
     * returns false if pinNumber is already in the table.
     *
     * @param integer $pinNumber
     * @return bool
     */
    private function checkPinNumber($pinNumber): bool
    {
        $em = $this->getDoctrine()->getManager();
        $gamepin = $em->getRepository(Gamepin::class)->findOneBy(array(
            'pinNumber' => $pinNumber
        ));
        return $gamepin == null;
    }

    /**
     * Launch one question:
     *
     * @Route("/question/{gamepin}/{idq}", name="oc_launch_question", requirements={
     * "gamepin": "\d+", "idq": "\d+" }, methods={"GET"})
     */
    public function questionAction(Request $request, Gamepin $gamepin, $idq)
    {
        $session = $request->getSession();
        if ($session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin->getPinNumber()) {
            $em = $this->getDoctrine()->getManager();
            $quiz = $gamepin->getQuiz();
            if ($quiz == null) {
                throw new NotFoundHttpException("Launchquestion can't get quiz with gamepin " . $gamepin->getPinNumber());
            }
            $nbqTot = $quiz->getNbQuestions();
            if ($idq <= $nbqTot) {
                $question = $quiz->getQCMs()->get($idq - 1);
                if ($question == Null) {
                    throw new NotFoundHttpException("Launchquestion can't get question in quiz ");
                }

                $texte = $question->getQuestion();
                $tempsrep = $question->getTemps();

                // retrieve Timer in db
                $timer = $em->getRepository(Timer::class)->findOneBy(array(
                    'gamepin' => $gamepin
                ));
                if ($timer == null) {
                    throw new NotFoundHttpException("No timer for gamepin " . $gamepin->getPinNumber());
                }
                $hdebut = time();

                $timer->setHdebut($hdebut);
                $timer->setHfin($hdebut + $tempsrep);
                $timer->setQNumber($idq);

                $em->persist($timer);
                $em->flush();

                $request->getSession()
                    ->getFlashBag()
                    ->add('notice', 'Début de la question.');

                return $this->render('OCQuizlaunch\question.html.twig', array(
                    'idq' => $idq,
                    'gamepin' => $gamepin,
                    'texte' => $texte,
                    'question' => $question,
                    'tempsrep' => $tempsrep
                ));
            } else { /* if( idq > nbqTot) */
                return $this->redirect($this->generateUrl('oc_launch_stat', array(
                    'gamepin' => $gamepin->getId()
                )));
            }
        } else
            return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
    }
    /**
     * Get nb users for a gamepin
     * lightweight service called by ajax
     * @Route("/getnbusers/{gamepin}", name="oc_launch_getnbusers", requirements={
     * "gamepin": "\d+" }, methods={"GET"})
     */
    public function getNbUsers(Request $request, Gamepin $gamepin)
    {
//        $session = $request->getSession();
//        if ($session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin->getPinNumber()) {
            
            $em = $this->getDoctrine()->getManager();
            
            /*
             * Retrieves entries associated to users
             */
            $pointsTotaux = $em->getRepository(PointQuestion::class)->findBy(array(
                'gamepin' => $gamepin,
                'idq' => 0
            ));
            
            $size = count($pointsTotaux);
            return new Response($size, Response::HTTP_OK);
 //       }
       return new Response("Session Error", Response::HTTP_NOT_FOUND);
        
    }
    /**
     * Launch one question:
     *
     * @Route("/score/{gamepin}/{idq}", name="oc_launch_score", requirements={
     * "gamepin": "\d+", "idq": "\d+" }, methods={"GET"})
     */
    public function scoreAction(Request $request, Gamepin $gamepin, $idq)
    {
        /**
         * TODO check algorithm and unused vars
         */
        $session = $request->getSession();
        if ($session->has('creatorGamepin') && $session->get('creatorGamepin') == $gamepin->getPinNumber()) {
            $em = $this->getDoctrine()->getManager();
            $QCMs = $gamepin->getQuiz()->getQCMs();
            $lastIdq = $gamepin->getQuiz()->getNbQuestions();
            $qcm = $QCMs->get($idq - 1);
            // answers to this question number
            $reponsesQuestionsTimers = $em->getRepository(ReponseQuestion::class)->findBy([
                'gamepin' => $gamepin,
                'qcm' => $qcm
            ]);

            // all entries associated to question 0 that is used to sum the points
            // when a player registers this entry is created
            $pointsTotaux = $em->getRepository(PointQuestion::class)->findBy(array(
                'gamepin' => $gamepin,
                'idq' => 0
            ));

            $pointsByPseudo = array();
            // creates pointsByPseudo => associative array to PointQuestion objects
            // associated to qnumber 0 (total of points)
            // key is the pseudo user
            foreach ($pointsTotaux as $pointQuestion) {
                $pointsByPseudo[$pointQuestion->getPseudoJoueur()] = $pointQuestion;
            }

            $pointsQuestions = array();

            // for players that send a answer to that question
            // there may be less answers than registered players
            foreach ($reponsesQuestionsTimers as $answer) {
                $pseudo = $answer->getPseudoUser();
                $onePQ = new PointQuestion();
                $em->persist($onePQ);
                $onePQ->setGamepin($gamepin);
                $onePQ->setPseudojoueur($pseudo);
                $onePQ->setIdq($idq);
                $onePQ->setPointqx($answer->getPoints());

                // modify pointQuestion for total points of pseudo 
                // related object comes from db is already persisted 
                // will be saved at next flush
                $pointsByPseudo[$pseudo]->setPointqx($pointsByPseudo[$pseudo]->getPointqx() + $answer->getPoints()) ;
                $pointsQuestions[$pseudo] = $onePQ;
            }

            $em->flush();
            $totalDisplay = self::prepareDisplay($pointsByPseudo);
            $currentDisplay = self::prepareDisplay($pointsQuestions);
            return $this->render('OCQuizlaunch\score.html.twig', array(
                'idq' => $idq,
                'lastidq' => $lastIdq,
                'gamepin' => $gamepin,
                'pointsQuestion' => $currentDisplay,
                'pointsTotaux' => $totalDisplay,
                'reponsesJustes' => $qcm->getReponsesJustes()
            ));
        } else {
            return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
        }
    }

    /**
     * Stats after launch
     * Ends the game and removes every data associated to a pinNumber
     *
     * @Route("/stats/{gamepin}", name="oc_launch_stat", requirements={
     * "gamepin": "\d+" }, methods={"GET"})
     */
    public function statsAction(Request $request, Gamepin $gamepin)
    {
        $em = $this->getDoctrine()->getManager();
    
        $pointsTotaux = $em->getRepository(PointQuestion::class)->findBy(array(
            'gamepin' => $gamepin,
            'idq' => 0
        ));
        
        $pointsByPseudo = array();
        
        foreach ($pointsTotaux as $pointQuestion) {
            $pointsByPseudo[$pointQuestion->getPseudoJoueur()] = $pointQuestion;
        }
        // Clean database
        self::cleanDB($gamepin);

        $toDisplay = self::prepareDisplay($pointsByPseudo);
        return $this->render('OCQuizlaunch\stats.html.twig', array(
            'pointsTot' => $toDisplay,
            'gamepin' => $gamepin
        ));
    }
    private function cleanDB(Gamepin $gamepin){
        $em = $this->getDoctrine()->getManager();
        $reponsesQuestions = $em->getRepository(ReponseQuestion::class)->findBy([
            'gamepin' => $gamepin,
        ]);
        foreach ($reponsesQuestions as $response) {
            $em->remove($response);
        }
        
        $pointsQs = $em->getRepository(PointQuestion::class)->findBy([
            'gamepin' => $gamepin,
        ]);
        foreach ($pointsQs as $pointsQ) {
            $em->remove($pointsQ);
        }
        
        $timers = $em->getRepository(Timer::class)->findBy(array(
            'gamepin' => $gamepin
        ));
        foreach ($timers as $timer) {
            $em->remove($timer);
        }
        $em->remove($gamepin);
        $em->flush();
    }
    /**
     * Prepare array containing quiz result to display 
     * the 10 firsts entries
     * @param array $toPrepare
     * @return array
     */
    private function prepareDisplay($toPrepare) : array
    {
        $toDisplay = array();
        $finalDisplay = array();
        foreach($toPrepare as $key => $pointQuestion) {
            $toDisplay[$key] = $pointQuestion->getPointqx();
        }
        arsort($toDisplay, SORT_NUMERIC);
        if(count($toDisplay) < 10 ){
            $finalDisplay = $toDisplay;
        } else {
            reset($toDisplay);
            for ($i = 0; $i < 10; $i++ ) {
                $finalDisplay[key($toDisplay)] = current($toDisplay);
                next($toDisplay);
            }
        }
        arsort($finalDisplay);
        return $finalDisplay;
    }
}
