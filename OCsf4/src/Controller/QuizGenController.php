<?php
namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\User;
use App\Form\QuizType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 *
 * @Route("/quizgen")
 */
class QuizGenController extends AbstractController
{
     
    /**
     * Lists all quizs => redirect to quizdis
     *
     * @Route("/", name="oc_quizgen_homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('oc_quizdis_select'));
    }

    /**
     * Add a quiz
     * @Route("/new", name="oc_quizgen_new")
     * @Route("/add", name="oc_quizgen_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $quiz = new Quiz();

        $form = $this->createForm(QuizType::class, $quiz, array());

        if ($request->isMethod('POST')) {

            if ($this->getUser() == null) {
                $quiz->setAuthor(0);
                $quiz->setAcces('public');
            } else {
                $quiz->setAuthor($this->getUser()
                    ->getId());
                $quiz->setAcces('private');
            }

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($quiz);
                $QCMS = $quiz->getQCMs();
                foreach ($QCMs as $key => $QCM) {
                    $QCM->setIdq($key+1);
                    $QCM->setQuiz($quiz);
                }
                $em->flush();

                $request->getSession()
                    ->getFlashBag()
                    ->add('notice', 'Vous avez créé un quiz avec succès.');

                return $this->redirect($this->generateUrl('oc_quizgen_view', array(
                    'id' => $quiz->getId()
                )));
            }
        }
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('OCQuizgen\add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit a quiz
     *
     * @Route("/edit/{id}", name="oc_quizgen_edit", requirements={
     * "id": "\d+" }))
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quiz $quiz)
    {
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }
        $form = $this->createForm(QuizType::class, $quiz);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($quiz->getQCMs() as $QCM) {
                    var_dump($QCM);
                    $QCM->setQuiz($quiz);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($quiz);
                $em->flush();
                $request->getSession()
                    ->getFlashBag()
                    ->add('notice', 'Vous avez modifié un quiz avec succès.');
                return $this->redirect($this->generateUrl('oc_quizgen_view', array(
                    'id' => $quiz->getId()
                )));
            }
        }
        return $this->render('OCQuizgen\edit.html.twig', array(
            'form' => $form->createView(),
            'quiz' => $quiz
        ));
    }

    /**
     * View a quiz
     *
     * @Route("/view/{id}", name="oc_quizgen_view", requirements={
     * "id": "\d+" }))
     * @Method("GET")
     */
    public function viewAction(Request $request, Quiz $quiz)
    {
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }

        $idAuteur = $quiz->getAuthor();
        $acces = $quiz->getAcces();
        if ($idAuteur == 0)
            $auteur = 'Anonyme';
        else {
            $em = $this->getDoctrine()->getManager();
            $auteur = $em->getRepository(User::class)
                ->find($idAuteur)
                ->getUserName();

            /*
             * $userManager = $this->get('fos_user.user_manager');
             * $auteur = $userManager->findUserBy(array('id' => $idAuteur))->getUsername();
             */
        }

        if (($idAuteur != 0) && ($this->getUser() != null) && $idAuteur == $this->getUser()->getId())
            return $this->render('OCQuizgen\view.html.twig', array(
                'quiz' => $quiz,
                'auteur' => $auteur
            ));
        else if ($acces == 'public')
            return $this->render('OCQuizgen\viewpublic.html.twig', array(
                'quiz' => $quiz,
                'auteur' => $auteur
            ));
        else
            throw new NotFoundHttpException("Vous n'avez pas accès au quiz ");
    }

    /*
     * Seems unused
     */
    private function menuAction($limit)
    {
        $listQuizs = $this->getDoctrine()
            ->getManager()
            ->getRepository(Quiz::class)
            ->findBy(array(
            'acces' => 'public'
        ), array(
            'id' => 'desc'
        ), $limit, 0);
        return $this->render('OCQuizgen\menu.html.twig', array(
            'listQuizs' => $listQuizs
        ));
    }

    /**
     * Lists all public quizs
     * TODO remove fos_user management
     *
     * @Route("/list", name="oc_quizgen_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listQuizs = $em->getRepository(Quiz::class)->findBy(array(
            'acces' => 'public'
        ), array(
            'id' => 'desc'
        ));
        $listNoms = null;
        foreach ($listQuizs as $quiz) {
            $idAuteur = $quiz->getAuthor();
            if ($idAuteur == 0)
                $listNoms[$idAuteur] = 'Anonyme';
            else {
                $auteur = $em->getRepository(User::class)
                    ->find($idAuteur)
                    ->getUserName();
                $listNoms[$idAuteur] = $auteur;
                // $userManager = $this->get('fos_user.user_manager');
                // $listNoms[$idAuteur] = $userManager->findUserBy(array('id' => $idAuteur))->getUsername();
            }
        }

        return $this->render('OCQuizgen\list.html.twig', array(
            'listQuizs' => $listQuizs,
            'listNoms' => $listNoms
        ));
    }

    /**
     * Lists a user quizs
     * TODO remove fos_user management
     *
     * @Route("/mylist", name="oc_quizgen_mylist")
     * @Method("GET")
     */
    public function mylistAction()
    {
        if ($this->getUser() != NULL) {
            $listQuizs = $this->getDoctrine()
                ->getManager()
                ->getRepository(Quiz::class)
                ->findBy(array(
                'author' => $this->getUser()
                    ->getId()
            ), array(
                'id' => 'desc'
            ));
            return $this->render('OCQuizgen\mylist.html.twig', array(
                'listQuizs' => $listQuizs,
                'auteur' => $this->getUser()
                    ->getUsername()
            ));
        } else
            return $this->redirect($this->generateUrl('fos_user_registration_register'));
    }

    /**
     * Delete a quiz
     *
     * @Route("/delete/{id}", name="oc_quizgen_delete", requirements={
     * "id": "\d+" }))
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Quiz $quiz)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isSubmitted() && $form->isValid()) { // Ici, isValid ne vérifie donc que le CSRF
                                                            // On supprime l'article
                $em = $this->getDoctrine()->getManager();
                $em->remove($quiz);
                $em->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Le quiz a été supprimé.');

                // Puis on redirige vers l'accueil
                return $this->redirect($this->generateUrl('oc_quizgen_homepage'));
            }
        }
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('OCQuizgen\delete.html.twig', array(
            'quiz' => $quiz,
            'form' => $form->createView()
        ));
    }

    /**
     * Duplicate a quiz
     *
     * @Route("/delete/{id}", name="oc_quizgen_duplicate", requirements={
     * "id": "\d+" }))
     * @Method({"GET","POST"})
     */
    public function duplicateAction(Request $request, Quiz $oldQuiz)
    {
        $newQuiz = new Quiz();

        $em = $this->getDoctrine()->getManager();

        if (null === $oldQuiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }

        // $nQuiz->setQuiz(aQuiz->getQuiz());
        $newQuiz = clone $oldQuiz;

        $form = $this->createForm(QuizType::class, $newQuiz, array(
            'user' => $this->getUser()
        ));
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            /**
             * TODO check that object is deeply copied and not referenced *
             */
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($newQuiz);

                foreach ($newQuiz->getQCMs() as $QCM) {
                    $QCM->setQuiz($newQuiz);
                }
                $em->flush();
                $request->getSession()
                    ->getFlashBag()
                    ->add('notice', 'Vous avez dupliqué un quiz avec succès');
                return $this->redirect($this->generateUrl('oc_quizgen_view', array(
                    'id' => $newQuiz->getId()
                )));
            }
        }
        return $this->render('OCQuizgen\duplicate.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
