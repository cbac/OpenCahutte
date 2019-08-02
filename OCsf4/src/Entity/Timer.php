<?php

namespace OC\QuizlaunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizlaunchBundle\Entity\TimerRepository")
 */
class Timer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="gamepin", type="integer")
     */
    private $gamepin;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="quizid", type="integer")
     */
    private $quizid;

    /**
     * @var integer
     *
     * @ORM\Column(name="question", type="integer")
     */
    private $question;

    /**
     * @var integer
     *
     * @ORM\Column(name="hfin", type="integer")
     */
    private $hfin;

   /**
     * @var \DateTime
     *
     * @ORM\Column(name="hdebut", type="integer")
     */
    private $hdebut;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gamepin
     *
     * @param integer $gamepin
     * @return Timer
     */
    public function setGamepin($gamepin)
    {
        $this->gamepin = $gamepin;

        return $this;
    }

    /**
     * Get gamepin
     *
     * @return integer 
     */
    public function getGamepin()
    {
        return $this->gamepin;
    }

    /**
     * Set question
     *
     * @param integer $question
     * @return Timer
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return integer 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set hfin
     *
     * @param \DateTime $hfin
     * @return Timer
     */
    public function setHfin($hfin)
    {
        $this->hfin = $hfin;

        return $this;
    }

    /**
     * Get hfin
     *
     * @return \DateTime 
     */
    public function getHfin()
    {
        return $this->hfin;
    }

    /**
     * Set quizid
     *
     * @param integer $quizid
     * @return Timer
     */
    public function setQuizid($quizid)
    {
        $this->quizid = $quizid;

        return $this;
    }

    /**
     * Get quizid
     *
     * @return integer 
     */
    public function getQuizid()
    {
        return $this->quizid;
    }

    /**
     * Set hdebut
     *
     * @param \DateTime $hdebut
     * @return Timer
     */
    public function setHdebut($hdebut)
    {
        $this->hdebut = $hdebut;

        return $this;
    }

    /**
     * Get hdebut
     *
     * @return \timestamp 
     */
    public function getHdebut()
    {
        return $this->hdebut;
    }

    /**
     * Set idcreateur
     *
     * @param integer $idcreateur
     * @return Timer
     */
    public function setIdcreateur($idcreateur)
    {
        $this->idcreateur = $idcreateur;

        return $this;
    }

    /**
     * Get idcreateur
     *
     * @return integer 
     */
    public function getIdcreateur()
    {
        return $this->idcreateur;
    }
}
