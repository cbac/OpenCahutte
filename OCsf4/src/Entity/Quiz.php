<?php


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizgenBundle\Entity\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $acces;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="nbQuestions", type="integer")
     */
    private $nbQuestions;
    
    
    
	
	/**
	 * @ORM\OneToMany(targetEntity="OC\QuizgenBundle\Entity\QCM", mappedBy="quiz", cascade={"persist", "remove"})
	 */
    private $QCMs;

    public function __construct()
    {
		$this->date = new \Datetime();
		$this->QCMs = new ArrayCollection();
		$this->type="qcm";
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
     * Set id
     *
     * @param string $id
     * @return Quiz
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
  
    

    /**
     * Set nom
     *
     * @param string $nom
     * @return Quiz
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Quiz
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Quiz
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Quiz
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Quiz
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nbQuestions
     *
     * @param integer $nbQuestions
     * @return Quiz
     */
    public function setNbQuestions($nbQuestions)
    {
        $this->nbQuestions = $nbQuestions;

        return $this;
    }

    /**
     * Get nbQuestions
     *
     * @return integer 
     */
    public function getNbQuestions()
    {
        return $this->nbQuestions;
    }

    /**
     * Add QCMs
     *
     * @param \OC\QuizgenBundle\Entity\QCM $qCMs
     * @return Quiz
     */
    public function addQCM(\OC\QuizgenBundle\Entity\QCM $QCM)
    {
        $this->QCMs[] = $QCM;
		
        return $this;
    }

    /**
     * Remove QCMs
     *
     * @param \OC\QuizgenBundle\Entity\QCM $qCMs
     */
    public function removeQCM(\OC\QuizgenBundle\Entity\QCM $qCMs)
    {
        $this->QCMs->removeElement($qCMs);
    }

    /**
     * Get QCMs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQCMs()
    {
        return $this->QCMs;
    }
	
	//cloner l'objet
	public function __clone()
	{
		if ($this->id) {
			$this->date = new \Datetime();
		}
	}

    /**
     * Set acces
     *
     * @param string $acces
     * @return Quiz
     */
    public function setAcces($acces)
    {
        $this->acces = $acces;

        return $this;
    }

    /**
     * Get acces
     *
     * @return string 
     */
    public function getAcces()
    {
        return $this->acces;
    }
}
