<?php

namespace CiscoSystems\AuditBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CiscoSystems\AuditBundle\Model\MetadataInterface;
use CiscoSystems\AuditBundle\Entity\Element;

/**
 * @ORM\Entity
 * @ORM\Table(name="cisco_audit__form")
 */
class Form extends Element
{
    /**
     * @var boolean Is the form active
     *
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @var string Label for the trigger flag
     *
     * @ORM\Column(type="string",name="flag_label")
     */
    protected $flagLabel;

    /**
     * @var boolean Are multiple answer allowed on flagged questions
     *
     * @ORM\Column(type="boolean",name="allow_multi_answer")
     */
    protected $allowMultipleAnswer;

    /**
     * @ORM\Column(name="created_at",type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var DoctrineCommon\Collections\ArrayCollection sections that belong to this form
     *
     * @ORM\OneToMany(targetEntity="CiscoSystems\AuditBundle\Entity\Section", mappedBy="form")
     */
    protected $sections;

    /**
     * @var DoctrineCommon\Collections\ArrayCollection audits that are using this form
     *
     * @ORM\OneToMany(targetEntity="CiscoSystems\AuditBundle\Entity\Audit", mappedBy="form")
     */
    protected $audits;

    /**
     * @var CiscoSystems\AuditBundle\Model\MetadataInterface metadata for this form
     *
     * @ORM\OneToOne(targetEntity="CiscoSystems\AuditBundle\Model\MetadataInterface", mappedBy="form")
     */
    protected $metadata;

    public function __construct()
    {
        parent::__construct();
        $this->active = TRUE;
        $this->allowMultipleAnswer = FALSE;
        $this->sections = new ArrayCollection();
        $this->audits = new ArrayCollection();
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
     * Set active
     *
     * @param boolean $active
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setActive( $boolean )
    {
        $this->active = $boolean;

        return $this;
    }

    /**
     * Get flagLabel
     *
     * @return string
     */
    public function getFlagLabel()
    {
        return $this->flagLabel;
    }

    /**
     * Set flagText
     *
     * @param string $flagLabel
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setFlagLabel( $flagLabel )
    {
        $this->flagLabel = $flagLabel;

        return $this;
    }

    /**
     * Get allowMultipleAnswer
     *
     * @return type
     */
    public function getAllowMultipleAnswer()
    {
        return $this->allowMultipleAnswer;
    }

    /**
     * Set allowMultipleAnswer
     *
     * @param boolean $boolean
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setAllowMultipleAnswer( $boolean )
    {
        $this->allowMultipleAnswer = $boolean;

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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set sections
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $sections
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setSections( \Doctrine\Common\Collections\ArrayCollection $sections = NULL )
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Add a section
     *
     * @param CiscoSystems\AuditBundle\Entity\Section $section
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function addSection( \CiscoSystems\AuditBundle\Entity\Section $section )
    {
        if( !$this->sections->contains( $section ))
        {
            $section->setForm( $this );
            $this->sections->add( $section );

            return $this;
        }

        return FALSE;
    }

    /**
     * Add all section in ArrayColleciton sections to ArrayCollection $this->sections
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $sections
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function addSections( \Doctrine\Common\Collections\ArrayCollection $sections )
    {
        foreach( $sections as $section )
        {
            $this->addSection( $section );
        }

        return $this;
    }

    /**
     * Remove sections
     *
     * @param CiscoSystems\AuditBundle\Entity\Section $section
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function removeSection( \CiscoSystems\AuditBundle\Entity\Section $section )
    {
        if( $this->sections->contains( $section ))
        {
            $index = $this->sections->indexOf( $section );
            $rem = $this->sections->get( $index );
            $rem->setForm( NULL );
            $this->sections->removeElement($section);

            return $this;
        }

        return FALSE;
    }

    /**
     * Remove all sections
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function removeAllSection()
    {
        foreach( $this->sections as $section )
        {
            $this->removeSection( $section );
        }

        return $this;
    }

    /**
     * Set audits
     *
     * @param \Doctrine\Common\Collections\Collection $audits
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setAudits( \Doctrine\Common\Collections\Collection $audits = NULL )
    {
        $this->audits = $audits;

        return $this;
    }

    /**
     * Get audits
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAudits()
    {
        return $this->audits;
    }

    /**
     * Add an Audit to ArrayColletion audits
     *
     * @param \CiscoSystems\AuditBundle\Entity\Audit $audit
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function addAudit( \CiscoSystems\AuditBundle\Entity\Audit $audit )
    {
        if( !$this->audits->contains( $audit ))
        {
            $audit->setForm( $this );
            $this->audits->add( $audit );

            return $this;
        }

        return FALSE;
    }

    /*
     * Add all audit in ArrayColleciton audits to ArrayCollection $this->audits
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $audits
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function addAudits( \Doctrine\Common\Collections\ArrayCollection $audits )
    {
        foreach( $audits as $audit )
        {
            $this->addAudit( $audit );
        }

        return $this;
    }

    /**
     * Remove Audit from ArrayColletion audits
     *
     * @param CiscoSystems\AuditBundle\Entity\Audit $audit
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function removeAudit( \CiscoSystems\AuditBundle\Entity\Audit $audit )
    {
        if( $this->audits->contains( $audit ))
        {
            $index = $this->audits->indexOf( $audit );
            $rem = $this->audits->get( $index );
            $rem->setForm( NULL );
            $this->audits->removeElement( $audit );

            return $this;
        }

        return FALSE;
    }

    /**
     * Remove all Audit from ArrayColletion audits
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function removeAllAudit()
    {
        foreach ( $this->audits as $audit )
        {
            $this->removeAudit( $audit );
        }

        return $this;
    }

    /**
     * Get metadata
     *
     * @return CiscoSystems\AuditBundle\Model\MetadataInterface
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata
     *
     * @param CiscoSystems\AuditBundle\Model\MetadataInterface $metadata
     *
     * @return CiscoSystems\AuditBundle\Entity\Form $this
     */
    public function setMetadata( \CiscoSystems\AuditBundle\Model\MetadataInterface $metadata = NULL )
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}