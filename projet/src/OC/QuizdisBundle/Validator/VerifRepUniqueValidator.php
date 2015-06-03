<?php
namespace OC\QuizdisBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifRepUniqueValidator extends ConstraintValidator
{
  public function validate($reponseQuestion, Constraint $constraint)
  {
	$em = $this->getDoctrine()->getManager();
	$quiz = $em
			->getRepository('OCQuizgenBundle:Timer')
			->findBy(
				array('gamepin' => $reponseQuestion->getGamepin()), // Critere
				array('datefin' => 'desc'),        // Tri
			)
	;
		
    // Pour l'instant, on considère comme flood tout message de moins de 3 caractères
    if ($reponseQuestion->getTime()) {
      // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message de la contrainte
      $this->context->addViolation($constraint->message);
    }
  }
}