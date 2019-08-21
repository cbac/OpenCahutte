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

class QuizTest extends TestCase
{

    public function testCtr()
    {
        $quiz = new Quiz();

        // check set/get on relations
        $quiz = new Quiz();
        $qcms = $quiz->getQCMs();
        $this->assertEquals(0, count($qcms));
    }

    public function testAddQcm()
    {
        $quiz = new Quiz();

        $qcm = new QCM();
        $qcm->setIdq(10);
        $qcm->setTemps(10);
        $qcm->setQuestion('Test');
        $qcm->setRep1('test');
        $qcm->setRep2('test');
        $qcm->setRep3('test');
        $qcm->setRep4('test');
        $qcm->setJuste1(true);
        $qcm->setJuste2(true);
        $qcm->setJuste3(true);
        $qcm->setJuste4(true);
        $quiz->addQCM($qcm);
        $this->assertEquals($quiz, $qcm->getQuiz());
        $qcms = $quiz->getQCMs();
        $this->assertEquals(1, $qcms->count());
        foreach ($qcms as $qcmbis) {
            $this->assertEquals($qcm, $qcmbis);
        }
        $quiz->removeQCM($qcm);
        $qcms = $quiz->getQCMs();
        $this->assertEquals(0, $qcms->count());
    }
    public function testDB(){
        $quiz = new Quiz();
        
        $qcm = new QCM();
        $qcm->setIdq(10);
        $qcm->setTemps(10);
        $qcm->setQuestion('Test');
        $qcm->setRep1('test');
        $qcm->setRep2('test');
        $qcm->setRep3('test');
        $qcm->setRep4('test');
        $qcm->setJuste1(true);
        $qcm->setJuste2(true);
        $qcm->setJuste3(true);
        $qcm->setJuste4(true);
        $quiz->addQCM($qcm);

    }
}

