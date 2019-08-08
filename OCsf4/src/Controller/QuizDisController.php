<?php
namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Timer;
use App\Entity\User;
use App\Entity\ReponseQuestion;
use App\Entity\PointQuestion;
use App\Entity\Gamepin;
use App\Form\GamepinType;
use App\Form\PseudoType;
use App\Form\PlayType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @Route("/quizdis")
 */
class QuizDisController extends AbstractController
{

    /**
     * Lists all quizs
     * @Route("/home", name="oc_home")
     * @Route("/", name="oc_quizdis_select")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $gamepin = new Gamepin();
        $form = $this->createForm('App\Form\GamepinType', $gamepin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('oc_quizdis_pseudo', array(
                'gamepin' => $gamepin->getGamepin()
            )));
        }
        return $this->render('OCQuizdis\index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     *
     * @Route("/pseudo/{gamepin}", name="oc_quizdis_pseudo",requirements={
     * "gamepin": "\d+" })
     * @Method({"GET", "POST"})
     */
    public function pseudoAction(Request $request, $gamepin)
    {
        $pointQuestion = new PointQuestion();

        $form = $this->createForm('App\Form\PseudoType', $pointQuestion);

        if ($request->isMethod('POST')) {
            $pointQuestion->setQuizid(0);
            $pointQuestion->setIdq(0);
            $pointQuestion->setGamepin($gamepin);
            $pointQuestion->setPointqx(0);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $session = $request->getSession();
                $session->set('pseudo', $pointQuestion->getPseudojoueur());

                $em = $this->getDoctrine()->getManager();
                $em->persist($pointQuestion);
                $em->flush();

                return $this->redirect($this->generateUrl('oc_quizdis_play', array(
                    'gamepin' => $gamepin
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
     * @Route("/{gamepin}", name="oc_quizdis_play",
     *  requirements={"gamepin": "\d+" })
     * @Method({"GET", "POST"})
     */
    public function playAction(Request $request, $gamepin)
    {
        $em = $this->getDoctrine()->getManager();
        $timers = $em->getRepository(Timer::class)->findByGamepin($gamepin);
        if (null == $timers) {
            throw new NotFoundHttpException("Unknown gamepin " . $gamepin);
        }
        $id = $timers[0]->getQuizid();
        $quiz = $em->getRepository(Quiz::class)->find($id);
        if (null === $quiz) {
            throw new NotFoundHttpException("Can't find quiz for " . $gamepin);
        }
        $idAuteur = $quiz->getAuthor();
        if ($idAuteur == 0) {
            $auteur = "anonyme";
        } else {
            /* $userManager = $this->get('fos_user.user_manager');
            $auteur = $userManager->findUserBy(array(
                'id' => $idAuteur
            ))->getUsername();
            */
            $auteur = $em->getRepository(User::class)->find($idAuteur)->getUserName();
            
        }

        $statReponse = new ReponseQuestion();
        $class = array(
            "btn btn-primary",
            "btn btn-success",
            "btn btn-warning",
            "btn btn-danger"
        );
        $form = array();
        for ($i = 0; $i < 4; $i ++) {
            $form[$i] = $this->createForm('App\Form\PlayType', $statReponse, array(
                'rep' => chr(65 + $i),
                'class' => $class[$i]
            ));
        }
        if ($request->isMethod('POST')) {
            $rep = $request->get('oc_quizdisbundle_play')['reponseDonnee'];
            $idRep = ord($rep) - 65;

            $statReponse->setGamepin($gamepin);
            $session = $request->getSession();
            $statReponse->setUser($session->get('pseudo'));

            $form[$idRep]->handleRequest($request);

            if ($form[$idRep]->isValid()) {
                $em->persist($statReponse);
                $em->flush();
                return $this->redirect($this->generateUrl('oc_quizdis_play', array(
                    'gamepin' => $gamepin
                )));
            }
        }
        return $this->render('OCQuizdis\play.html.twig', array(
            'quiz' => $quiz,
            'reponse' => $statReponse,
            'auteur' => $auteur,
            'form' => array(
                $form[0]->createView(),
                $form[1]->createView(),
                $form[2]->createView(),
                $form[3]->createView()
            )
        ));
    }
}
