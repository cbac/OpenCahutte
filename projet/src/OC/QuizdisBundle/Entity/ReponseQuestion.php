<?php

namespace OC\QuizdisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OC\QuizdisBundle\Validator as OCQuizdisAssert;

/**
 * ReponseQuestion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizdisBundle\Entity\ReponseQuestionRepository")
 * @OCQuizdisAssert\VerifRepUnique
 */
class ReponseQuestion
{
	public function __construct()
    {
        $this->time = time();
    }
 
	/**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\Column(type="integer")
     */
    private $gamepin;
	
	/**
     * @ORM\Column(type="integer")
     */
    private $user;
	
    /**
     * @ORM\Column(type="integer")
     */
    private $time;
	
    /**
     * @ORM\Column(type="string", length=1)
     */
    private $reponseDonnee;

    /**
     * @ORM\Column(type="integer")
     */
    private $question;
	
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
     * @return ReponseQuestion
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
     * Set user
     *
     * @param integer $user
     * @return ReponseQuestion
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return ReponseQuestion
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set reponseDonnee
     *
     * @param string $reponseDonnee
     * @return ReponseQuestion
     */
    public function setReponseDonnee($reponseDonnee)
    {
        $this->reponseDonnee = $reponseDonnee;

        return $this;
    }

    /**
     * Get reponseDonnee
     *
     * @return string 
     */
    public function getReponseDonnee()
    {
        return $this->reponseDonnee;
    }

    /**
     * Set question
     *
     * @param integer $question
     * @return ReponseQuestion
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
}
