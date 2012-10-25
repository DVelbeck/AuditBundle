<?php

namespace WG\AuditBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="wgauditform")
 */
class AuditForm
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(name="created_at",type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="WG\AuditBundle\Entity\AuditFormSection", mappedBy="auditform")
     */
    protected $sections;

    /**
     * @var integer
     */
    protected $weightPercentage;

    /**
     * @var integer
     */
    protected $weight;

    public function __construct()
    {
        $this->active = true;
        $this->sections = new ArrayCollection();
        $this->weight = 0;
        $this->weightPercentage = 0;
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
     * Set title
     *
     * @param string $title
     * @return AuditForm
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return AuditForm
     */
    public function setDescription( $description )
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return AuditForm
     */
    public function setActive( $active )
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AuditForm
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add a section
     *
     * @param WG\AuditBundle\Entity\AuditFormSection $section
     * @return AuditForm
     */
    public function addSection(AuditFormSection $section)
    {
        $section->setAuditform( $this );
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove sections
     *
     * @param WG\AuditBundle\Entity\AuditFormSection $sections
     */
    public function removeSection(AuditFormSection $sections)
    {
        $this->sections->removeElement($sections);
    }

    /**
     * Get sections
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

        /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     */
    public function setWeight( $weight )
    {
        $this->weight = $weight;
    }

    /**
     * Get weightPercentage
     *
     * @return integer
     */
    public function getWeightPercentage()
    {
        return $this->weightPercentage;
    }

    /**
     * Set weightPercentage
     *
     * @param integer $weightPercentage
     */
    public function setWeightPercentage( $weightPercentage )
    {
        $this->weightPercentage = $weightPercentage;
    }

    /**
     * add weight and weight's percentage to current variable
     * $weight and $weightpercentage
     *
     * @param integer $weight
     * @param integer $weightPercentage
     */
    public function addScore( $weight, $weightPercentage )
    {
        $divisor = $this->weight + $weight;
        $this->weightPercentage = $this->weightPercentage * $this->weight / $divisor + $weightPercentage * $weight / $divisor;
        $this->weight += $weight;
    }
}