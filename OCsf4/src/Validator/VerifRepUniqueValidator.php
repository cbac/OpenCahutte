<?php
namespace App\Validator;
use App\Entity\Timer;
use App\Entity\ReponseQuestion;

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
		$timer = $this
			->em
			->getRepository(Timer::class)
			->findOneBy(
				array('gamepin' => $reponseQuestion->getGamepin())
			)
		;
		if ($timer == NULL )
			$this->context->addViolation(" Quiz pas encore lancé !");
		else {
			$hfin=$timer->getHfin();
			$hdebut=$timer->getHdebut();
			$reponsesDejaEnregistrees = $this
				->em
				->getRepository(ReponseQuestion::class)
				->findReponsesUtilisateur($reponseQuestion->getGamepin(),
				    $reponseQuestion->getPseudoUser(),$hdebut,$hfin)
			;
			if($reponsesDejaEnregistrees != NULL)
				$this->context->addViolation(" Vous avez déjà répondu à la question actuelle !");
			else if ($reponseQuestion->getTime() > $hfin)
				$this->context->addViolation(" Temps écoulé !");
			else
				$reponseQuestion->setTimer($timers[0]);
		}
	}
}