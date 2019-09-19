<?php
namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Access;
use App\Entity\User;
use App\Form\QuizType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/quizgen")
 */
class QuizGenController extends AbstractController
{
     
    /**
     * Lists all quizs => redirect to quizdis
     *
     * @Route("/", name="oc_quizgen_homepage", methods={"GET"})
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('oc_quizdis_select'));
    }

    /**
     * Add a quiz
     * @Route("/new", name="oc_quizgen_new", methods={"GET", "POST"})
     * @Route("/add", name="oc_quizgen_add", methods={"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $quiz = new Quiz();
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        
        if ($request->isMethod('POST')) {
            
            if ($this->getUser() == null) {
                $quiz->setAuthor(null);
                $pubAccess = $em->getRepository(Access::class)->findOneByName('public');
                $quiz->setAccess($pubAccess);
            } else {
                $quiz->setAuthor($this->getUser());
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($quiz);
                $QCMs = $quiz->getQCMs();
                foreach ($QCMs as $key => $qcm) {
                    $qcm->setIdq($key);
                    $qcm->setQuiz($quiz);
                    $em->persist($qcm);
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
     * "id": "\d+" }, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Quiz $quiz)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        
        if ($request->isMethod('POST')) {
            if ($this->getUser() == null) {
                $quiz->setAuthor(null);
                $pubAccess = $em->getRepository(Access::class)->findOneByName('public');
                $quiz->setAccess($pubAccess);
            } else {
                $quiz->setAuthor($this->getUser());
            }
            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($quiz->getQCMs() as $QCM) {
                    $QCM->setQuiz($quiz);
                    $em->persist($QCM);
                }
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
     * "id": "\d+" }, methods={"GET", "POST"})
     */
    public function viewAction(Request $request, Quiz $quiz)
    {
        if (null === $quiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }
        $auteur = $quiz->getAuthor();
        $access = $quiz->getAccess();

        if ($this->getUser() != null && $auteur == $this->getUser()) {
            return $this->render('OCQuizgen\view.html.twig', array(
                'quiz' => $quiz,
                'auteur' => $auteur->getEmail(),
                'user' => $auteur->getEmail()
            ));
        } else {
            if ($access->getName() == 'public') {

                return $this->render('OCQuizgen\view.html.twig', array(
                    'quiz' => $quiz,
                    'auteur' => $auteur == null ? 'Anonyme' : $auteur->getEmail(),
                    'user' => $this->getUser() == null ? 'Anonyme' : $this->getUser()->getEmail()
                    
                ));
            } else {
                throw new NotFoundHttpException("Vous n'avez pas accès au quiz ");
            }
        }
    }

    /**
     * Lists all public quizs
     * TODO remove fos_user management
     *
     * @Route("/list", name="oc_quizgen_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listQuizs = $em->getRepository(Quiz::class)->findPublicQuizes();
        $listNoms = array();
        foreach ($listQuizs as $quiz) {
            $auteur = $quiz->getAuthor();
            if ($auteur == null)
                $listNoms[] = 'Anonyme';
            else {
                $listNoms[] = $auteur->getEmail();
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
     * @Route("/mylist", name="oc_quizgen_mylist", methods={"GET"})
     */
    public function mylistAction(Request $request)
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
            return $this->redirect($this->generateUrl('app_login'));
    }

    /**
     * Delete a quiz
     *
     * @Route("/delete/{id}", name="oc_quizgen_delete", requirements={
     * "id": "\d+" }, methods={"GET","POST"})
     */
    public function deleteAction(Request $request, Quiz $quiz)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 
                $submittedToken = $request->request->get('token');
                
                // 'delete-item' is the same value used in the template to generate the token
                if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                    // ... do something, like deleting an object
                
                $em = $this->getDoctrine()->getManager();
                $em->remove($quiz);
                $em->flush();

                // On définit un message flash
                $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Le quiz a été supprimé.');
                } else {
                    // On définit un message flash
                    $this->get('session')
                    ->getFlashBag()
                    ->add('info', 'Bad CRSF when deleting Quiz.');
                }
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
     * @Route("/duplicate/{id}", name="oc_quizgen_duplicate", requirements={
     * "id": "\d+" }, methods={"GET","POST"})
     */
    public function duplicateAction(Request $request, Quiz $oldQuiz)
    {
        $newQuiz = new Quiz();

        $em = $this->getDoctrine()->getManager();

        if (null === $oldQuiz) {
            throw new NotFoundHttpException("Le quiz n'existe pas.");
        }
        $newQuiz = clone $oldQuiz;
        $form = $this->createForm(QuizType::class, $newQuiz, array(
        ));
        $newQuiz->setAuthor($this->getUser());
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
