<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator as OCQuizdisAssert;

/**
 * Gamepin
 *
 * @ORM\Entity()
 * @OCQuizdisAssert\VerifGamepinExists
 */
class Gamepin
{
 
	/**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz")
     */
    private $quiz;
    
	/**
     * @ORM\Column(type="integer")
     */
    private $pinNumber;
    
    function __construct(Quiz $quiz=null){
        /* TODO check unicity */
        if($quiz !== null) {
            $this->setQuiz($quiz);
            $this->pinNumber = rand(1, 999999);
        }
        
    }
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
     * Set pinNumber
     *
     * @param integer $gamepin
     * @return Gamepin
     */
    public function setPinNumber($pinNumber) : self
    {
        $this->pinNumber = $pinNumber;

        return $this;
    }

    /**
     * Get pinNumber
     *
     * @return integer 
     */
    public function getPinNumber() : ? int
    {
        return $this->pinNumber;
    }
    /**
     * Set quiz
     *
     * @param Quiz $quiz
     * @return QCM
     */
    public function setQuiz(Quiz $quiz=null) : self
    {
        $this->quiz = $quiz;
        
        return $this;
    }
    
    /**
     * Get quiz
     *
     * @return Quiz
     */
    public function getQuiz() : ? Quiz
    {
        return $this->quiz;
    }
}
