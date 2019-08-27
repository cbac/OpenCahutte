<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator as OCQuizlaunchAssert;

/**
 * PointQuestion
 *
 * @ORM\Entity()
 * @OCQuizlaunchAssert\VerifPseudoUnique
 */
class PointQuestion
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
     * @ORM\Column(name="idq", type="integer")
     */
    private $idq;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gamepin")
     */
    private $gamepin;

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
     * Set idq
     *
     * @param integer $idq
     * @return PointQuestion
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
     * @return PointQuestion
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
     * @return PointQuestion
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
     * @return PointQuestion
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
     * @return PointQuestion
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
     * @return PointQuestion
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
