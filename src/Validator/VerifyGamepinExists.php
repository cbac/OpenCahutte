<?php
namespace App\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VerifyGamepinExists extends Constraint {

	
	public function validatedBy()
	{
		return 'oc_quizdis_verifgamepinexists'; // Ici, on fait appel à l'alias du service
	}
	
	public function getTargets()
	{
		return self::CLASS_CONSTRAINT;
	}
  
}