<?php
namespace OC\QuizdisBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifRepUniqueValidator extends ConstraintValidator
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
  
	public function validate($reponseQuestion, Constraint $constraint)
	{
		$timers = $this
			->em
			->getRepository('OCQuizlaunchBundle:Timer')
			->findBy(
				array('gamepin' => $reponseQuestion->getGamepin()), // Critere
				array('question' => 'desc'),        // Tri
				1 								// on n'en prend qu'un
			)
		;
		if ($timers == NULL || $timers[0]->getQuestion() == 0)
			$this->context->addViolation(" Quiz pas encore lancé !");
		else {
			$hfin=$timers[0]->getHfin();
			$hdebut=$timers[0]->getHdebut();
			$reponsesDejaEnregistrees = $this
				->em
				->getRepository('OCQuizdisBundle:ReponseQuestion')
				->getReponsesUtilisateur($reponseQuestion->getGamepin(),$reponseQuestion->getUser(), $hdebut, $hfin)
			;
			if($reponsesDejaEnregistrees != NULL)
				$this->context->addViolation(" Vous avez déjà répondu à la question actuelle !");
			else if ($reponseQuestion->getTime() > $hfin)
				$this->context->addViolation(" Temps écoulé !");
			else
				$reponseQuestion->setQuestion($timers[0]->getQuestion());
		}
	}
}