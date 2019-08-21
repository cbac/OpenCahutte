<?php
namespace App\Entity;
use App\Entity\Quiz;
use Doctrine\ORM\Mapping as ORM;

/**
 * QCM
 *
 * @ORM\Entity()
 */
class QCM
{
    public function __construct()
    {/*
		$this->setJuste1(false);
		$this->setJuste2(false);
		$this->setJuste3(false);
		$this->setJuste4(false); */
    } 
	
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
     * @ORM\Column(name="temps", type="integer")
     */
    private $temps;
 
    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     */
     private $question;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rep1", type="string", length=255)
     */
     private $rep1;

    /**
     * @var string
     *
     * @ORM\Column(name="rep2", type="string", length=255)
     */
    private $rep2;

    /**
     * @var string
     *
     * @ORM\Column(name="rep3", type="string", length=255)
     */
    private $rep3;

    /**
     * @var string
     *
     * @ORM\Column(name="rep4", type="string", length=255)
     */
    private $rep4;

	/**
     * @var boolean
     *
     * @ORM\Column(name="juste1", type="boolean")
     */
    private $juste1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="juste2", type="boolean")
     */
    private $juste2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="juste3", type="boolean")
     */
    private $juste3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="juste4", type="boolean")
     */
    private $juste4;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="QCMs")
     */
    private $quiz;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return QCM
     */
    public function setQuestion($question) : self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion() : ? string
    {
        return $this->question;
    }

    /**
     * Set rep1
     *
     * @param string $rep1
     * @return QCM
     */
    public function setRep1($rep1) : self
    {
        $this->rep1 = $rep1;

        return $this;
    }

    /**
     * Get rep1
     *
     * @return string 
     */
    public function getRep1() : ? string
    {
        return $this->rep1;
    }

    /**
     * Set rep2
     *
     * @param string $rep2
     * @return QCM
     */
    public function setRep2($rep2) : self
    {
        $this->rep2 = $rep2;

        return $this;
    }

    /**
     * Get rep2
     *
     * @return string 
     */
    public function getRep2() : ? string
    {
        return $this->rep2;
    }

    /**
     * Set rep3
     *
     * @param string $rep3
     * @return QCM
     */
    public function setRep3($rep3) : self
    {
        $this->rep3 = $rep3;

        return $this;
    }

    /**
     * Get rep3
     *
     * @return string 
     */
    public function getRep3() : ? string
    {
        return $this->rep3;
    }

    /**
     * Set rep4
     *
     * @param string $rep4
     * @return QCM
     */
    public function setRep4($rep4) : self
    {
        $this->rep4 = $rep4;

        return $this;
    }

    /**
     * Get rep4
     *
     * @return string 
     */
    public function getRep4() : ? string
    {
        return $this->rep4;
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
    public function getQuiz() : Quiz
    {
        return $this->quiz;
    }

    /**
     * Set juste1
     *
     * @param boolean $juste1
     * @return QCM
     */
    public function setJuste1($juste1) : self
    {
        $this->juste1 = $juste1;

        return $this;
    }

    /**
     * Get juste1
     *
     * @return boolean 
     */
    public function getJuste1() : bool
    {
        return $this->juste1;
    }

    /**
     * Set juste2
     *
     * @param boolean $juste2
     * @return QCM
     */
    public function setJuste2($juste2) : self
    {
        $this->juste2 = $juste2;

        return $this;
    }

    /**
     * Get juste2
     *
     * @return boolean 
     */
    public function getJuste2() : bool
    {
        return $this->juste2;
    }

    /**
     * Set juste3
     *
     * @param boolean $juste3
     * @return QCM
     */
    public function setJuste3($juste3) : self
    {
        $this->juste3 = $juste3;

        return $this;
    }

    /**
     * Get juste3
     *
     * @return boolean 
     */
    public function getJuste3() : bool
    {
        return $this->juste3;
    }

    /**
     * Set juste4
     *
     * @param boolean $juste4
     * @return QCM
     */
    public function setJuste4($juste4) : self
    {
        $this->juste4 = $juste4;

        return $this;
    }

    /**
     * Get juste4
     *
     * @return boolean 
     */
    public function getJuste4() : bool
    {
        return $this->juste4;
    }
	/**
	 * 
	 * @return string[]
	 */
	public function getReponsesPossibles() : array
	{
		return array(
			1 => $this->getRep1(),
			2 => $this->getRep2(),
			3 => $this->getRep3(),
			4 => $this->getRep4()
		);
	}
	/**
	 *
	 * @return string[]
	 */
	public function getReponsesJustes() : array
	{
		$reponsesJustes=array();
		
		if($this->getJuste1())
			array_push($reponsesJustes,"A");
		if($this->getJuste2())
			array_push($reponsesJustes,"B");
		if($this->getJuste3())
			array_push($reponsesJustes,"C");
		if($this->getJuste4())
			array_push($reponsesJustes,"D");
			
		return $reponsesJustes;
	}

    /**
     * Set temps
     *
     * @param integer $temps
     * @return QCM
     */
    public function setTemps($temps) : self
    {
        $this->temps = $temps;

        return $this;
    }

    /**
     * Get temps
     *
     * @return integer 
     */
    public function getTemps() : int
    {
        return $this->temps;
    }
}
