<?php

namespace OC\QuizgenBundle\Controller;


use OC\QuizgenBundle\Entity\Quiz;
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
    $formBuilder = $this->get('form.factory')->createBuilder('form', $quiz);
    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('date',      'date')
      ->add('nom',     'text')
      ->add('author',    'text')
      ->add('category',    'text')
      ->add('type',    'text')
      ->add('save',      'submit')
    ;
    $form = $formBuilder->getForm();
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('OCQuizgenBundle:Default:add.html.twig', array(
      'form' => $form->createView(),
    ));
  } 
  
  public function viewAction($id)
  {
     
    $em = $this->getDoctrine()->getManager();
    // On récupère le quiz $id
    $quiz = $em
      ->getRepository('OCQuizgenBundle:Default')
      ->find($id)
    ;
    if (null === $quiz) {
      throw new NotFoundHttpException("Le quiz d'id ".$id." n'existe pas.");
    }

    return $this->render('OCQuizgenBundle:Default:view.html.twig', array(
      'quiz'           => $quiz
    ));
  }
  
  
}
