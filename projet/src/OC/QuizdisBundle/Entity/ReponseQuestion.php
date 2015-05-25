<?php

namespace OC\QuizdisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReponseQuestion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OC\QuizdisBundle\Entity\ReponseQuestionRepository")
 */
class ReponseQuestion
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
     * @var boolean
     *
     * @ORM\Column(name="juste", type="boolean")
     */
    private $juste;

    /**
     * @var float
     *
     * @ORM\Column(name="temps", type="float")
     */
    private $temps;


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
     * Set juste
     *
     * @param boolean $juste
     * @return ReponseQuestion
     */
    public function setJuste($juste)
    {
        $this->juste = $juste;

        return $this;
    }

    /**
     * Get juste
     *
     * @return boolean 
     */
    public function getJuste()
    {
        return $this->juste;
    }

    /**
     * Set temps
     *
     * @param float $temps
     * @return ReponseQuestion
     */
    public function setTemps($temps)
    {
        $this->temps = $temps;

        return $this;
    }

    /**
     * Get temps
     *
     * @return float 
     */
    public function getTemps()
    {
        return $this->temps;
    }
}
