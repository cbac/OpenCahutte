<?php
namespace Jul\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Jul\TestBundle\Entity\AdvertSkillRepository")
 */
class AdvertSkill
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="level", type="string", length=255)
   */
  private $level;

  /**
   * @ORM\ManyToOne(targetEntity="Jul\TestBundle\Entity\Advert")
   * @ORM\JoinColumn(nullable=false)
   */
  private $advert;

  /**
   * @ORM\ManyToOne(targetEntity="Jul\TestBundle\Entity\Skill")
   * @ORM\JoinColumn(nullable=false)
   */
  private $skill;

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
     * Set level
     *
     * @param string $level
     * @return AdvertSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set advert
     *
     * @param \Jul\TestBundle\Entity\Advert $advert
     * @return AdvertSkill
     */
    public function setAdvert(\Jul\TestBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;
    
        return $this;
    }

    /**
     * Get advert
     *
     * @return \Jul\TestBundle\Entity\Advert 
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set skill
     *
     * @param \Jul\TestBundle\Entity\Skill $skill
     * @return AdvertSkill
     */
    public function setSkill(\Jul\TestBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;
    
        return $this;
    }

    /**
     * Get skill
     *
     * @return \Jul\TestBundle\Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
