<?php
// src/OC/UserBundle/OCUserBundle.php

namespace OC\UserBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OCUserBundle\Entity\UserManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OCUserBundleController extends Controller 
{

  
  public function removeAction($username) {
  
      //  $em = $this->getDoctrine()->getManager();

      //  /** @var \Star9988\ModelBundle\Entity\User $user */
      //  $user = $this->getDoctrine()->getRepository('ModelBundle:User')->find($id);

      //  $em->remove($user);
     //   $em->flush();
     //  return new RedirectResponse("star9988_user_user_index"); 
     
     // Pour récupérer le service UserManager du bundle
      $userManager = $this->get('fos_user.user_manager');
      
      // Pour charger un utilisateur
      $user = $userManager->findUserByUsername($username);
    
      $connected = $userManager->findUserByUsername($this->container->get('security.context')
                    ->getToken()
                    ->getUser());
                    
      if ( $user ==  null || $user != $connected ) {
              
	 return $this->render('OCUserBundle::error.html.twig');
		
       }  
       
       else {
       
       // Pour supprimer un utilisateur
	$userManager->deleteUser($user); 
	
	return $this->render('OCUserBundle::remove.html.twig', array(
			  'username' => $username
		  ));
       
        
       }
      
   }   
  
}
