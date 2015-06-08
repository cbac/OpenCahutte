<?php

namespace OC\QuizlaunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OC\QuizlaunchBundle\Validator as OCQuizlaunchAssert;

/**
 * Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizlaunchBundle\Entity\SessionRepository")
 * @OCQuizlaunchAssert\VerifPseudoUnique
 */
class Session
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
     * @ORM\Column(name="quizid", type="integer")
     */
    private $quizid;

    /**
     * @var integer
     *
     * @ORM\Column(name="idq", type="integer")
     */
    private $idq;

    /**
     * @var integer
     *
     * @ORM\Column(name="gamepin", type="integer")
     */
    private $gamepin;

    /**
     * @var integer
     *
     * @ORM\Column(name="idcreateur", type="integer")
     */
    private $idcreateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudojoueur;

    /**
     * @var integer
     *
     * @ORM\Column(name="pointqx", type="integer")
     */
    private $pointqx;


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
     * Set quizid
     *
     * @param integer $quizid
     * @return Session
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
     * Set idq
     *
     * @param integer $idq
     * @return Session
     */
    public function setIdq($idq)
    {
        $this->idq = $idq;

        return $this;
    }

    /**
     * Get idq
     *
     * @return integer 
     */
    public function getIdq()
    {
        return $this->idq;
    }

    /**
     * Set gamepin
     *
     * @param integer $gamepin
     * @return Session
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
     * Set idcreateur
     *
     * @param integer $idcreateur
     * @return Session
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

    /**
     * Set nomjoueur
     *
     * @param string $nomjoueur
     * @return Session
     */
    public function setNomjoueur($nomjoueur)
    {
        $this->nomjoueur = $nomjoueur;

        return $this;
    }

    /**
     * Get nomjoueur
     *
     * @return string 
     */
    public function getNomjoueur()
    {
        return $this->nomjoueur;
    }

    /**
     * Set pointqx
     *
     * @param integer $pointqx
     * @return Session
     */
    public function setPointqx($pointqx)
    {
        $this->pointqx = $pointqx;

        return $this;
    }

    /**
     * Get pointqx
     *
     * @return integer 
     */
    public function getPointqx()
    {
        return $this->pointqx;
    }

    /**
     * Set idjoueur
     *
     * @param integer $idjoueur
     * @return Session
     */
    public function setIdjoueur($idjoueur)
    {
        $this->idjoueur = $idjoueur;

        return $this;
    }

    /**
     * Get idjoueur
     *
     * @return integer 
     */
    public function getIdjoueur()
    {
        return $this->idjoueur;
    }

    /**
     * Set pseudojoueur
     *
     * @param integer $pseudojoueur
     * @return Session
     */
    public function setPseudojoueur($pseudojoueur)
    {
        $this->pseudojoueur = $pseudojoueur;

        return $this;
    }

    /**
     * Get pseudojoueur
     *
     * @return integer 
     */
    public function getPseudojoueur()
    {
        return $this->pseudojoueur;
    }
}
