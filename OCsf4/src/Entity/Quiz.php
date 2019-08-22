<?php

namespace App\Entity;
use App\Entity\QCM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Quiz
 *
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quizzes")
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
	 * @ORM\OneToMany(targetEntity="App\Entity\QCM", mappedBy="quiz", cascade={"persist", "remove"})
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
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param string $id
     * @return self
     */
    public function setId($id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return self
     */
    public function setNom($nom) : self
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
     * @return self
     */
    public function setAuthor($author=null) : self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor():? User
    {
        return $this->author;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return self
     */
    public function setDate($date):self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return self
     */
    public function setCategory($category) : self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory() :?string
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type) : self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Get nbQuestions
     *
     * @return int
     */
    public function getNbQuestions() : int
    {
        return $this->QCMs->count();
    }

    /**
     * Add QCMs
     *
     * @param QCM $QCM
     * @return self
     */
    public function addQCM(QCM $QCM) : self
    {
        $this->QCMs->add($QCM) ;
        $QCM->setIdq($this->QCMs->key());
        $QCM->setQuiz($this);
        return $this;
    }

    /**
     * Remove QCMs
     *
     * @param QCM $QCM
     * @return self
     */
    public function removeQCM(QCM $QCM) : self
    {
        $this->QCMs->removeElement($QCM);
        $QCM->setQuiz();
        return $this;
    }

    /**
     * Get QCMs
     *
     * @return Collection 
     */
    public function getQCMs() : Collection
    {
        return $this->QCMs;
    }
	/** TODO check clone **/
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
     * @return self
     */
    public function setAcces($acces) : self
    {
        $this->acces = $acces;
        return $this;
    }

    /**
     * Get acces
     *
     * @return string 
     */
    public function getAcces() : string
    {
        return $this->acces;
    }
}
