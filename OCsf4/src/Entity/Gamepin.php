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
     * @ORM\Column(type="integer")
     */
    private $gamepin;
		
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
     * Set gamepin
     *
     * @param integer $gamepin
     * @return Gamepin
     */
    public function setGamepin($gamepin) : self
    {
        $this->gamepin = $gamepin;

        return $this;
    }

    /**
     * Get gamepin
     *
     * @return integer 
     */
    public function getGamepin() : ? int
    {
        return $this->gamepin;
    }

}
