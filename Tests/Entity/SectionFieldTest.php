<?php
namespace CiscoSystems\AuditBundle\Tests\Entity;

use CiscoSystems\AuditBundle\Entity\Field;
use CiscoSystems\AuditBundle\Entity\SectionField;
use CiscoSystems\AuditBundle\Entity\Section;

class SectionFieldTest extends \PHPUnit_Framework_TestCase
{
    protected $section;
    protected $field;
    protected $relation;

    protected function setUp()
    {
        parent::setUp();
        $this->field = new Field();
        $this->section = new Section();
        $this->relation = new SectionField();
    }

    /**
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::getArchived
     */
    public function testArchived()
    {
        $archived = TRUE;
        $this->relation->setArchived( $archived );

        $this->assertTrue( $this->relation->getArchived() );
    }

    /**
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::setPosition
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::getPosition
     */
    public function testPosition()
    {
        $position = 1;
        $this->relation->setPosition( $position );

        $this->assertEquals( $position + 1, $this->relation->getPosition() );
    }

    /**
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::setField
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::getField
     */
    public function testField()
    {
        $field = $this->field;
        $this->relation->setField( $field );

        $this->assertEquals( $field, $this->relation->getField() );
    }

    /**
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::setSection
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::getSection
     */
    public function testSection()
    {
        $section = $this->section;
        $this->relation->setSection( $section );

        $this->assertEquals( $section, $this->relation->getSection() );
    }

    /**
     * @covers CiscoSystems\AuditBundle\Entity\SectionField::getType
     */
    public function testType()
    {
        $this->assertEquals(
            \CiscoSystems\AuditBundle\Entity\Relation::TYPE_SECTIONFIELD,
            $this->relation->getType()
        );
    }
}