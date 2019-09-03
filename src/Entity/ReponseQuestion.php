<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator as OCQuizdisAssert;

/**
 * ReponseQuestion
 * @ORM\Entity(repositoryClass="App\Repository\ReponseQuestionRepository")
 * @OCQuizdisAssert\VerifRepUnique
 */
class ReponseQuestion
{
	public function __construct()
                      {
                          $this->time = time();
                      }
		
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Timer")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $timer;

 
	/**
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
     * @ORM\Column(type="string", length=255)
     */
    private $pseudoUser;
	
    /**
     * @ORM\Column(type="integer")
     */
    private $time;
	
    /**
     * @ORM\Column(type="string", length=1)
     */
    private $reponseDonnee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QCM", inversedBy="reponseQuestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qcm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;
	
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
     * Set pseudoUser
     *
     * @param integer $user
     * @return ReponseQuestion
     */
    public function setPseudoUser($user)
    {
        $this->pseudoUser = $user;

        return $this;
    }

    /**
     * Get pseudoUser
     *
     * @return integer 
     */
    public function getPseudoUser()
    {
        return $this->pseudoUser;
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
     * Set timer
     *
     * @param Timer $timer
     * @return ReponseQuestion
     */
    public function setTimer(Timer $timer)
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * Get timer
     *
     * @return Timer 
     */
    public function getTimer()
    {
        return $this->timer;
    }

    public function getQcm(): ?QCM
    {
        return $this->qcm;
    }

    public function setQcm(?QCM $qcm): self
    {
        $this->qcm = $qcm;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }
}
