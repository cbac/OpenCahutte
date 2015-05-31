<?php

namespace OC\SynchroBundle\Controller;

use OC\SynchroBundle\Synchro\ClickButtonEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OC\SynchroBundle\SynchroEvents;


class DefaultController extends Controller
{
	
    public function index1Action(Request $request)
    {
      $event = new ClickButtonEvents();

      // On déclenche l'évènement
      $this
        ->get('event_dispatcher')
        ->dispatch(ClickButtonEvents::synchro.click_button, $event)
      ;
        return $this->render('OCSynchroBundle:Default:index1.html.twig');
    }
	
	    public function index2Action()
    {
        return $this->render('OCSynchroBundle:Default:index2.html.twig');
    }
	
	    public function index3Action()
    {
        return $this->render('OCSynchroBundle:Default:index3.html.twig');
    }
}
