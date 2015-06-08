<?php
namespace OC\QuizlaunchBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VerifPseudoUnique extends Constraint{

	
	public function validatedBy()
	{
		return 'oc_quizlaunch_verifpseudounique'; // Ici, on fait appel à l'alias du service
	}
	
	public function getTargets()
	{
		return self::CLASS_CONSTRAINT;
	}
  
}