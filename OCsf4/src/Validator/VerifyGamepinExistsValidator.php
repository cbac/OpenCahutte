<?php
namespace App\Validator;

use App\Entity\Gamepin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VerifyGamepinExistsValidator extends ConstraintValidator
{

    private $requestStack;

    private $em;

    // Les arguments déclarés dans la définition du service arrivent au constructeur
    // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function validate($gamepin, Constraint $constraint)
    {
        $gamepinObject = $this->em->getRepository(Gamepin::class)->findOneBy(array(
            'pinNumber' => $gamepin->getPinNumber()
        ));
        if (null == $gamepinObject) {
            $this->context->addViolation("Gamepin: " . $gamepin->getPinNumber() . " not active");
        }
    }
}