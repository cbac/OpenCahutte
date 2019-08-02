<?php

namespace OC\QuizlaunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizlaunchBundle\Entity\StatsRepository")
 */
class Stats
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
     * @ORM\Column(name="pseudojoueur", type="string", length=255)
     */
    private $pseudojoueur;

    /**
     * @var integer
     *
     * @ORM\Column(name="quizid", type="integer")
     */
    private $quizid;

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
     * @var integer
     *
     * @ORM\Column(name="scoretot", type="integer")
     */
    private $scoretot;

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
     * Set pseudojoueur
     *
     * @param string $pseudojoueur
     * @return Stats
     */
    public function setPseudojoueur($pseudojoueur)
    {
        $this->pseudojoueur = $pseudojoueur;

        return $this;
    }

    /**
     * Get pseudojoueur
     *
     * @return string 
     */
    public function getPseudojoueur()
    {
        return $this->pseudojoueur;
    }

    /**
     * Set quizid
     *
     * @param integer $quizid
     * @return Stats
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
     * Set gamepin
     *
     * @param integer $gamepin
     * @return Stats
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
     * @return Stats
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
     * Set scoretot
     *
     * @param integer $scoretot
     * @return Stats
     */
    public function setScoretot($scoretot)
    {
        $this->scoretot = $scoretot;

        return $this;
    }

    /**
     * Get scoretot
     *
     * @return integer 
     */
    public function getScoretot()
    {
        return $this->scoretot;
    }
}
