<?php

namespace OC\QuizgenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QCM
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizgenBundle\Entity\QCMRepository")
 */
class QCM
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
     * @ORM\ManyToOne(targetEntity="OC\QuizgenBundle\Entity\Quiz", inversedBy="QCMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

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
     * Set question
     *
     * @param string $question
     * @return QCM
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set rep1
     *
     * @param string $rep1
     * @return QCM
     */
    public function setRep1($rep1)
    {
        $this->rep1 = $rep1;

        return $this;
    }

    /**
     * Get rep1
     *
     * @return string 
     */
    public function getRep1()
    {
        return $this->rep1;
    }

    /**
     * Set rep2
     *
     * @param string $rep2
     * @return QCM
     */
    public function setRep2($rep2)
    {
        $this->rep2 = $rep2;

        return $this;
    }

    /**
     * Get rep2
     *
     * @return string 
     */
    public function getRep2()
    {
        return $this->rep2;
    }

    /**
     * Set rep3
     *
     * @param string $rep3
     * @return QCM
     */
    public function setRep3($rep3)
    {
        $this->rep3 = $rep3;

        return $this;
    }

    /**
     * Get rep3
     *
     * @return string 
     */
    public function getRep3()
    {
        return $this->rep3;
    }

    /**
     * Set rep4
     *
     * @param string $rep4
     * @return QCM
     */
    public function setRep4($rep4)
    {
        $this->rep4 = $rep4;

        return $this;
    }

    /**
     * Get rep4
     *
     * @return string 
     */
    public function getRep4()
    {
        return $this->rep4;
    }

    /**
     * Set quiz
     *
     * @param \OC\QuizgenBundle\Entity\Quiz $quiz
     * @return QCM
     */
    public function setQuiz(\OC\QuizgenBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \OC\QuizgenBundle\Entity\Quiz 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set juste1
     *
     * @param boolean $juste1
     * @return QCM
     */
    public function setJuste1($juste1)
    {
        $this->juste1 = $juste1;

        return $this;
    }

    /**
     * Get juste1
     *
     * @return boolean 
     */
    public function getJuste1()
    {
        return $this->juste1;
    }

    /**
     * Set juste2
     *
     * @param boolean $juste2
     * @return QCM
     */
    public function setJuste2($juste2)
    {
        $this->juste2 = $juste2;

        return $this;
    }

    /**
     * Get juste2
     *
     * @return boolean 
     */
    public function getJuste2()
    {
        return $this->juste2;
    }

    /**
     * Set juste3
     *
     * @param boolean $juste3
     * @return QCM
     */
    public function setJuste3($juste3)
    {
        $this->juste3 = $juste3;

        return $this;
    }

    /**
     * Get juste3
     *
     * @return boolean 
     */
    public function getJuste3()
    {
        return $this->juste3;
    }

    /**
     * Set juste4
     *
     * @param boolean $juste4
     * @return QCM
     */
    public function setJuste4($juste4)
    {
        $this->juste4 = $juste4;

        return $this;
    }

    /**
     * Get juste4
     *
     * @return boolean 
     */
    public function getJuste4()
    {
        return $this->juste4;
    }
}
