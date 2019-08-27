<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timer
 *
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Gamepin")
     */
    private $gamepin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="qNumber", type="integer")
     */
    private $qNumber;

    /**
     * @var \DateTime
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
     * @return Gamepin 
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
    public function setQNumber($question)
    {
        $this->qNumber = $question;
        return $this;
    }

    /**
     * Get question
     *
     * @return integer 
     */
    public function getQNumber()
    {
        return $this->qNumber;
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
}
