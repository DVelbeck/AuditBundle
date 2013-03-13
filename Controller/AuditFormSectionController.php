<?php

namespace CiscoSystems\AuditBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CiscoSystems\AuditBundle\Entity\AuditFormSection;
use CiscoSystems\AuditBundle\Form\Type\AuditFormSectionType;

class AuditFormSectionController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        $sections = $repo->findAll();
        return $this->render( 'CiscoSystemsAuditBundle:AuditFormSection:index.html.twig', array(
            'sections' => $sections,
        ));
    }

    public function editAction( Request $request )
    {
        $edit = false;
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        $section = new AuditFormSection();
        if ( $request->get( 'section_id' ) )
        {
            $edit = true;
            $section = $repo->find( $request->get( 'section_id' ));
        }
        $auditForm = null;
        if( $request->get( 'form_id' ) )
        {
            $formRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditForm' );
            $auditForm = $formRepo->find( $request->get( 'form_id' ) );
            $section->setAuditForm( $auditForm );
        }
        $form = $this->createForm( new AuditFormSectionType(), $section);
        if ( null !== $request->get( $form->getName() ))
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $em->persist( $section );
                $em->flush();
                return $this->redirect( $this->generateUrl( 'cisco_auditform_edit', array(
                    'form_id'  => $section->getAuditForm()->getId(),
                )));
            }
        }
        $uFieldRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormField' );
        $uFields = $uFieldRepo->findBy( array ( 'section' => null ));

        /**
         * Performed for ajax request; Planned to be used with a modal box
        if ( $request->isXmlHttpRequest() )
        {
            return $this->render( 'CiscoSystemsAuditBundle:AuditFormSection:_edit.html.twig', array(
                'edit'      => $edit,
                'section'   => $section,
                'ufields'   => $uFields,
                'form'      => $form->createView(),
            ));
        }
        else*/ return $this->render( 'CiscoSystemsAuditBundle:AuditFormSection:edit.html.twig', array(
            'edit'          => $edit,
            'section'       => $section,
            'ufields'       => $uFields,
            'form'          => $form->createView(),
        ));
    }

    /**
     * Performed for ajax request
     * Planned to be used with a modal box
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @throws type
     */
    public function viewAction( Request $request )
    {
        $em = $this->getDoctrine()->getEntityManager();
        $sectionRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        if ( null === $section = $sectionRepo->find( $request->get( 'section_id' ) ))
        {
            throw $this->createNotFoundException( 'Section does not exist' );
        }
        return $this->render( 'CiscoSystemsAuditBundle:AuditFormSection:view.html.twig', array(
            'section' => $section,
        ));
    }

    /**
     * Delete Section and remove all reference from AuditForm
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return twig template
     * @throws type
     */
    public function deleteAction( Request $request )
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        if ( null !== $section = $repo->find( $request->get( 'section_id' ) ))
        {
//            $formRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditForm' );
            $auditform = $section->getAuditForm();
            $id = $auditform->getId();
            $section->setAuditForm( null );
            $section->removeAllField();
            $em->remove( $section );
            $em->flush();
            return $this->redirect( $this->generateUrl( 'cisco_auditform_edit', array(
                'form_id'   => $id,
            )));
        }
        throw $this->createNotFoundException( 'Section does not exist' );
    }

    /**
     * Remove field from section based on the section.id and field.id passed in
     * the url.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws type
     */
    public function removeAction( Request $request )
    {
        $em = $this->getDoctrine()->getEntityManager();
        $sectionRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        $section = $sectionRepo->find( $request->get( 'section_id' ));
        if ( null !== $section )
        {
            $fieldRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormField' );
            $field = $fieldRepo->find( $request->get( 'field_id' ));
            if ( null !== $field )
            {
                $section->removeField( $field );
                $em->persist( $section );
                $em->flush();
                if ( $request->isXmlHttpRequest() )
                {
                    $fields = $fieldRepo->findBy( array ( 'section' => null ));
                    return $this->render( 'CiscoSystemsAuditBundle:AuditFormField:_ulist.html.twig', array(
                        'ufields'   => $fields,
                        'section'   => $section,
                    ));
                }
                else return $this->redirect( $this->generateUrl( 'cisco_auditsection_edit', array (
                    'id' => $section->getId() )
                ));
            }
            throw $this->createNotFoundException( 'Field does not exist' );
        }
        throw $this->createNotFoundException( 'Section does not exist' );
    }

    /**
     * Add field to section based on the section.id and the field.id passed in
     * the url.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws type
     */
    public function addAction( Request $request )
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormSection' );
        $section = $repo->find( $request->get( 'section_id' ));
        if ( null !== $section )
        {
            $fieldRepo = $em->getRepository( 'CiscoSystemsAuditBundle:AuditFormField' );
            $field = $fieldRepo->find( $request->get( 'field_id' ));
            if ( null !== $field )
            {
                $section->addField( $field );
                $em->persist( $section );
                $em->flush();
                if ( $request->isXmlHttpRequest() )
                {
                    return $this->render( 'CiscoSystemsAuditBundle:AuditFormField:_load.html.twig', array(
                        'field'    => $field,
                        'section'  => $section,
                    ));
                }
                else return $this->redirect( $this->generateUrl( 'cisco_auditsection_edit', array (
                    'id' => $section->getId() )
                ));
            }
            throw $this->createNotFoundException( 'Field does not exist' );
        }
        throw $this->createNotFoundException( 'Section does not exist' );
    }
}
