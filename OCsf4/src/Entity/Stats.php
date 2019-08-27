<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stats
 *
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Gamepin")
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
     * Set gamepin
     *
     * @param Gamepin $gamepin
     * @return PointQuestion
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
