<?php
namespace OC\QuizlaunchBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifPseudoUniqueValidator extends ConstraintValidator
{
	private $requestStack;
	private $em;

	// Les arguments déclarés dans la définition du service arrivent au constructeur
	// On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
	public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
	{
		$this->requestStack = $requestStack;
		$this->em           = $em;
	}
  
	public function validate($session, Constraint $constraint)
	{
		$sessionjoueur = $this->em
			->getRepository('OCQuizlaunchBundle:Session')
			->findBy(
				array('gamepin'=>$session->getGamepin(), 'idq'=>0, 'pseudojoueur'=>$session->getPseudojoueur() )
			)
		;
		if ($sessionjoueur != null)
			$this->context->addViolation(" Pseudo déjà utilisé");
	}
}