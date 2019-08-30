<?php
namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Timer;
use App\Entity\ReponseQuestion;
use App\Entity\PointQuestion;
use App\Entity\Gamepin;
use App\Form\GamepinType;
use App\Form\PseudoType;
use App\Form\PlayType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuizDisController extends AbstractController
{

    /**
     * Lists all quizs
     *
     * @Route("/home", name="oc_home", methods={"GET", "POST"})
     * @Route("/", name="oc_quizdis_select", methods={"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $gamepin = new Gamepin();
        $form = $this->createForm(GamepinType::class, $gamepin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('oc_quizdis_pseudo', array(
                'pinNumber' => $gamepin->getPinNumber()
            )));
        }
        return $this->render('OCQuizdis\index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Choose a user pseudo name
     *
     * @Route("/pseudo/{pinNumber}", name="oc_quizdis_pseudo",requirements={
     * "pinNumber": "\d+" }, methods={"GET", "POST"})
     */
    public function pseudoAction(Request $request, $pinNumber)
    {
        $em = $this->getDoctrine()->getManager();
        $gamepin = $em->getRepository(Gamepin::class)->findOneBy(array(
            'pinNumber' => $pinNumber
        ));
        if ($gamepin == null) {
            throw new NotFoundHttpException("Unknown gamepin " . $gamepin);
        }
        $pointQuestion = new PointQuestion();

        $form = $this->createForm(PseudoType::class, $pointQuestion);

        if ($request->isMethod('POST')) {
            $pointQuestion->setIdq(0);
            $pointQuestion->setGamepin($gamepin);
            $pointQuestion->setPointqx(0);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $session = $request->getSession();
                $session->set('pseudo', $pointQuestion->getPseudojoueur());

                $em->persist($pointQuestion);
                $em->flush();

                return $this->redirect($this->generateUrl('oc_quizdis_play', array(
                    'pinNumber' => $gamepin->getPinNumber()
                )));
            }
        }
        return $this->render('OCQuizdis\pseudo.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Play quiz
     *
     * @Route("/play/{pinNumber}", name="oc_quizdis_play",
     *  requirements={"pinNumber": "\d+" }, methods={"GET", "POST"})
     */
    public function playAction(Request $request, $pinNumber)
    {
        $em = $this->getDoctrine()->getManager();
        $gamepin = $em->getRepository(Gamepin::class)->findOneBy([
            'pinNumber' => $pinNumber
        ]);
        if (null == $gamepin) {
            throw new NotFoundHttpException("Unknown gamepin " . $pinNumber);
        }
        $timer = $em->getRepository(Timer::class)->findOneByGamepin($gamepin);
        if (null == $timer) {
            throw new NotFoundHttpException("No timer for gamepin " . $pinNumber);
        }
        $quiz = $gamepin->getQuiz();
        if (null === $quiz) {
            dump($gamepin);
            throw new NotFoundHttpException("No quiz associated to " . $pinNumber);
        }
        $session = $request->getSession();
        $statReponse = new ReponseQuestion();
        
        $qnumber = $timer->getQNumber();
        $auteur = $quiz->getAuthor();
        if ($quiz->getAuthor() == null) {
            $authorName = "anonyme";
        } else {
            $authorName = $auteur->getEmail();
        }
        
        $class = array(
            "btn btn-primary",
            "btn btn-success",
            "btn btn-warning",
            "btn btn-danger"
        );
        $form = array();
        
        for ($i = 0; $i < 4; $i ++) {
            $form[$i] = $this->createForm(PlayType::class, $statReponse, array(
                'rep' => chr(65 + $i),
                'class' => $class[$i]
            ));
        }
        if ($qnumber < 1) {
            $session->getFlashBag()->add('notice', 'Quiz not started.');
        } else {
            if ($request->isMethod('POST')) {
                $qnumber = $timer->getQNumber() - 1;
                $qcm = $quiz->getQcms()->get($qnumber);
                if (null === $qcm) {
                    dump($quiz);
                    throw new NotFoundHttpException("No question associated to gamepin " . $pinNumber . " and question " . $qnumber);
                }
                $rep = $request->get('play')['reponseDonnee'];
                $idRep = ord($rep) - 65;

                $statReponse->setGamepin($gamepin);
                $statReponse->setPseudoUser($session->get('pseudo'));
                $statReponse->setTime(time());
                $statReponse->setQcm($qcm);

                $form[$idRep]->handleRequest($request);
                // get $statReponse.reponseDonnee from form
                if ($form[$idRep]->isSubmitted() && $form[$idRep]->isValid()) {
                    $em->persist($statReponse);
                    $em->flush();
                    return $this->redirect($this->generateUrl('oc_quizdis_play', array(
                        'pinNumber' => $gamepin->getPinNumber()
                    )));
                }
            }
        }
        return $this->render('OCQuizdis\play.html.twig', array(
            'quiz' => $quiz,
            'reponse' => $statReponse,
            'auteur' => $authorName,
            'form' => array(
                $form[0]->createView(),
                $form[1]->createView(),
                $form[2]->createView(),
                $form[3]->createView()
            )
        ));
    }
}
