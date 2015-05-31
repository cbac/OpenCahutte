<?php

namespace OC\SynchroBundle\Synchro;

class RedirectionListener
{
  public function processRedirect()
  {
	  return $this->processor->redirect();
  }
}