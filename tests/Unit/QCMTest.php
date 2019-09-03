<?php
namespace tests\Unit;
use PHPUnit\Framework\TestCase;
use App\Entity\Metier;
use App\Entity\TypePrestation;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Localisation;
use App\Entity\Evenement;
use App\Entity\QCM;
use App\Entity\Quiz;


class QCMTest  extends TestCase
{
    public function testCtr() {
        $qcm = new QCM();
        
        $qcm->setIdq(10);
        $this->assertEquals(10, $qcm->getIdq());
        $qcm->setTemps(10);
        $this->assertEquals(10, $qcm->getTemps());
        $qcm->setQuestion('Test');
        $this->assertEquals('Test', $qcm->getQuestion());
        $qcm->setRep1('test');
        $this->assertEquals('test',$qcm->getRep1());
        $qcm->setRep2('test');
        $this->assertEquals('test',$qcm->getRep2());
        $qcm->setRep3('test');
        $this->assertEquals('test',$qcm->getRep3());
        $qcm->setRep4('test');
        $this->assertEquals('test',$qcm->getRep4());
        $qcm->setJuste1(true);
        $this->assertTrue($qcm->getJuste1());
        $qcm->setJuste2(true);
        $this->assertTrue($qcm->getJuste2());
        $qcm->setJuste3(true);
        $this->assertTrue($qcm->getJuste3());
        $qcm->setJuste4(true);
        $this->assertTrue($qcm->getJuste4());
        // check set/get on relations
        $quiz = new Quiz();
        $qcm->setQuiz($quiz);
        $this->assertEquals($quiz, $qcm->getQuiz($quiz));
     }
     
}

