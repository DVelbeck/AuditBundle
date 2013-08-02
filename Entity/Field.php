<?php

namespace CiscoSystems\AuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CiscoSystems\AuditBundle\Entity\Element;

/**
 * @ORM\Entity(repositoryClass="CiscoSystems\AuditBundle\Entity\Repository\FieldRepository")
 * @ORM\Table(name="audit__field")
 */
class Field extends Element
{
    const DEFAULTWEIGHTVALUE = 5;

    /**
     * @var \CiscoSystems\AuditBundle\Entity\AuditSection AuditSection to which the AuditField belongs
     *
     * @ORM\OneToMany(targetEntity="CiscoSystems\AuditBundle\Entity\SectionField", mappedBy="field")
     */
    protected $sectionRelations;

    /**
     * @var array Array of string values: settable choices
     *
     * @ORM\Column(name="choices",type="array")
     */
    protected $choices;

    /**
     * @var \CiscoSystems\AuditBundle\Entity\Score Score associated with the AuditField
     *
     * @ORM\OneToMany(targetEntity="CiscoSystems\AuditBundle\Entity\Score", mappedBy="field")
     */
    protected $scores;

    /**
     * @var integer Weight for the AuditField
     *
     * @ORM\Column(name="weight",type="integer")
     */
    protected $weight;

    /**
     * @var boolean Flag/Trigger for the AuditField
     *
     * @ORM\Column(name="flag",type="boolean")
     */
    protected $flag;

    /**
     * @var boolean enabled/diabled AuditField check
     *
     * @ORM\Column(name="disabled",type="boolean")
     */
    protected $disabled;

    public function __construct()
    {
        parent::__construct();
        $this->flag = FALSE;
        $this->auditscores = new ArrayCollection();
        $this->disabled = FALSE;
        $this->weight = self::DEFAULTWEIGHTVALUE;
        $this->sectionRelations = new ArrayCollection();
    }

    /**
     * Get scores
     *
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Set scores
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $choices
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function setChoices( $choices )
    {
        $this->choices = $choices;

        return $this;
    }

    /**
     * Add score and its label
     *
     * @param string $choice
     * @param string $label
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function addChoice( $choice, $label )
    {
        $this->choices[ $choice ] = $label;

        return $this;
    }


    /**
     * Get auditscore
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getScores()
    {
        return $this->scores;
    }
    /**
     * Set auditscores
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $scores
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function setScores( \Doctrine\Common\Collections\ArrayCollection $scores = NULL )
    {
        $this->scores = $scores;

        return $this;
    }

    /**
     * Add an auditscore
     *
     * @param \CiscoSystems\AuditBundle\Entity\Score $score
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function addScore( \CiscoSystems\AuditBundle\Entity\Score $score )
    {
        if( count( $this->scores ) > 0 && !$this->scores->contains( $score ))
        {
            $this->scores->add( $score );
            $score->setField( $this );

            return $this;
        }

        return FALSE;
    }

    /**
     * Add auditscores
     *
     * @param array $scores
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function addScores( array $scores )
    {
        foreach( $scores as $score )
        {
            $this->addScore( $score );
        }

        return $this;
    }

    /**
     * Remove auditscores
     *
     * @param \CiscoSystems\AuditBundle\Entity\Score $scores
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function removeScore( \CiscoSystems\AuditBundle\Entity\Score $scores )
    {
        if( $this->scores->contains( $scores ) )
        {
            $index = $this->scores->indexOf( $scores );
            $rem = $this->scores->get( $index );
            $rem->setField( NULL );
            $this->scores->removeElement( $scores );

            return $this;
        }

        return FALSE;
    }

    /**
     * Remove all auditscores
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function removeAllScores()
    {
        foreach ( $this->scores as $score )
        {
            $this->removeScore( $score );
        }

        return $this;
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
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function setWeight( $weight )
    {
        $this->weight = ( $this->flag === FALSE ) ?
                        (( $weight > 0 ) ? $weight : self::DEFAULTWEIGHTVALUE ) :
                        self::DEFAULTWEIGHTVALUE ;

        return $this;
    }

    /**
     * Get flag
     *
     * @return boolean
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set flag
     *
     * @param boolean $flag
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function setFlag( $flag )
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set disabled
     *
     * @param boolean $boolean
     *
     * @return \CiscoSystems\AuditBundle\Entity\Field
     */
    public function setDisabled( $boolean = FALSE )
    {
        $this->disabled = $boolean;

        return $this;
    }

    public function getSections()
    {
        $sections = array();
        foreach( $this->sectionRelations as $relation )
        {
            $sections[] = $relation->getSection();
        }
        return $sections;
    }

    public function getSectionRelations()
    {
        return $this->sectionRelations;
    }

    public function setSectionRelations( ArrayCollection $relations )
    {
        $this->sectionRelations = $relations;

        return $this;
    }

    public function addSectionRelation( \CiscoSystems\AuditBundle\Entity\SectionField $relation )
    {
        if( !$this->sectionRelations->contains( $relation ))
        {
            $this->sectionRelations->add( $relation );

            return $this;
        }

        return FALSE;
    }

    public function removeSectionRelation( \CiscoSystems\AuditBundle\Entity\SectionField $relation )
    {
        if( $this->sectionRelations->contains( $relation ))
        {
            $relation->setArchived( TRUE );

            return $this;
        }

        return FALSE;
    }
}