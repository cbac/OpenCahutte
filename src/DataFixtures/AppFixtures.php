<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use App\Entity\QCM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $quiz = new Quiz();
        foreach ($this->getQuizData() as [$nom,$access,$category]) {
            $quiz->setDate(date_create());
            $quiz->setNom($nom);
            $quiz->setAccess($access);
            $quiz->setCategory($category);
        }
        
        foreach ($this->getQCMData() as $idq => [$question, $rep1, $j1, $rep2, $j2,
            $rep3, $j3, $rep4,$j4, $temps]) {
            $qcm = new QCM();
            $qcm->setIdq($idq);
            $qcm->setQuestion($question);
            $qcm->setRep1($rep1);
            $qcm->setRep2($rep2);
            $qcm->setRep3($rep3);
            $qcm->setRep4($rep4);
            $qcm->setJuste1($j1);
            $qcm->setJuste2($j2);
            $qcm->setJuste3($j3);
            $qcm->setJuste4($j4);
            $qcm->setTemps($temps);
            $quiz->addQCM($qcm);
        }
        $manager->persist($quiz);
        $manager->flush();
    }
    private function getQCMData()
    {
        yield ['test1','rep1', true, 'rep2', false, 'rep3', true, 'rep4', false, 10];
        
    }
    private function getQuizData()
    {
        yield ['test','public','Culture Générale'];     
    }
}
