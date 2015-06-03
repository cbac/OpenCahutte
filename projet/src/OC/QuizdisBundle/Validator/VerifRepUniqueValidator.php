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
		if(1) {
			$timers = $this
					->em
					->getRepository('OCQuizlaunchBundle:Timer')
					->findBy(
						array('gamepin' => $reponseQuestion->getGamepin()), // Critere
						array('hfin' => 'asc'),        // Tri
						1 								// on n'en prend qu'un
					)
			;
			if ($timers == NULL)
				$this->context->addViolation("Quiz pas encore lancé !");
			else {
				$timediff=$reponseQuestion->getTime() - $timers[0]->getHfin();  // 1 si négatif, 0 si positif
				if ($timediff > 0)
					$this->context->addViolation("Temps écoulé !");
				else {
					$hfin=$timers[0]->getHfin();
					$hdebut=$hfin-600;
					dump($hdebut);
					dump($hfin);
					$reponsesDejaEnregistrees = $this
							->em
							->getRepository('OCQuizdisBundle:ReponseQuestion')
							->getReponsesUtilisateur($reponseQuestion->getGamepin(),$reponseQuestion->getUser(), $hdebut, $hfin)
					;
					dump($reponsesDejaEnregistrees);
					if($reponsesDejaEnregistrees != NULL)
						$this->context->addViolation("Vous avez déjà répondu à la question actuelle !");
				}
			}
		}
	}
}