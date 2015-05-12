<?php

namespace OC\QuizgenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OCQuizgenBundle:Default:index.html.twig', array('name' => $name));
    }
}
