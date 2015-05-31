<?php
// src/OC/UserBundle/OCUserBundle.php

namespace OC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OCUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
  
  public function removeAction($id){
  
      
    // Pour récupérer le service UserManager du bundle
    $userManager = $this->get('fos_user.user_manager');

    // Pour charger un utilisateur
    $user = $userManager->findUserBy(array('id' => '$id'));
    
    // Pour supprimer un utilisateur
    $userManager->deleteUser($user);	
    
    return $this->render('OCUserBundle::remove.html.twig', array(
			'username' => $username,
		));
  
  }
  
}
