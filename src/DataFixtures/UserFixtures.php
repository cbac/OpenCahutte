<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
         private $passwordEncoder;
    
         public function __construct(UserPasswordEncoderInterface $passwordEncoder)
         {
             $this->passwordEncoder = $passwordEncoder;
        }
        
    public function load(ObjectManager $manager)
    {
        $this->LoadUsers($manager);

        $manager->flush();
    }
    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email,$plainPassword,$role]) {
            $user = new User();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);
            $user->addRole($role);
            $manager->persist($user);
        }
    }
    
    private function getUserData()
    {
        yield ['chris@localhost','chris','ROLE_USER'];
        yield ['anna@localhost','anna','ROLE_ADMIN'];
        
    }
}
