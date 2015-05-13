<?php

namespace OC\QuizgenBundle\Controller;


use OC\QuizgenBundle\Entity\Quiz;
use OC\QuizgenBundle\Form\QuizType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCQuizgenBundle:Default:index.html.twig');
    }
     public function addAction(Request $request){
     // On crée un objet Quiz
    $quiz = new Quiz();
    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->create(new QuizType(), $quiz);
    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    
    // À partir de maintenant, la variable $quiz contient les valeurs entrées dans le formulaire par le visiteur
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($quiz);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Vous avez créé un quiz avec succès.');

      return $this->redirect($this->generateUrl('oc_quizgen_view', array('id' => $quiz->getId())));
    }
         
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('OCQuizgenBundle:Default:add.html.twig', array(
      'form' => $form->createView(),
    ));
  } 
  
  public function viewAction($id)
  {
     
    $em = $this->getDoctrine()->getManager();
    // On récupère le $id
    $quiz = $em
      ->getRepository('OCQuizgenBundle:Quiz')
      ->find($id)
    ;
    if (null === $quiz) {
      throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
    }

    return $this->render('OCQuizgenBundle:Default:view.html.twig', array(
      'quiz'           => $quiz
    ));
  }
   public function menuAction($limit)
  {
   // test affichage avec des quizs hors BDD
    $listQuizs = array(
      array('id' => 2, 'nom' => 'Symfony2'),
      array('id' => 5, 'nom' => 'Appels système'),
      array('id' => 9, 'nom' => 'Flex et Bison')
    );

    return $this->render('OCQuizgenBundle:Default:menu.html.twig', array(
      'listQuizs' => $listQuizs
    ));
  }
  
  
}
