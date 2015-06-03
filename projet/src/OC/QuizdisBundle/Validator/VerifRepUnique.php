<?php
namespace OC\QuizdisBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class VerifRepUnique extends Constraint{
  public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, merci d'attendre un peu.";
}

public function getTargets()
{
    return self::CLASS_CONSTRAINT;
}