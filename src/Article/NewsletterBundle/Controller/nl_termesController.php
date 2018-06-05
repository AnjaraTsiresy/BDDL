<?php

namespace Article\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Article\NewsletterBundle\Entity\nl_termes;
use Article\NewsletterBundle\Form\nl_termesType;

/**
 * nl_termes controller.
 *
 * @Route("/nl_termes")
 */
class nl_termesController extends Controller
{

    /**
     * Lists all nl_termes entities.
     *
     * @Route("/", name="nl_termes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ArticleNewsletterBundle:nl_termes')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new nl_termes entity.
     *
     * @Route("/", name="nl_termes_create")
     * @Method("POST")
     * @Template("ArticleNewsletterBundle:nl_termes:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new nl_termes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('nl_termes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a nl_termes entity.
     *
     * @param nl_termes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(nl_termes $entity)
    {
        $form = $this->createForm(new nl_termesType(), $entity, array(
            'action' => $this->generateUrl('nl_termes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new nl_termes entity.
     *
     * @Route("/new", name="nl_termes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new nl_termes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a nl_termes entity.
     *
     * @Route("/{id}", name="nl_termes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArticleNewsletterBundle:nl_termes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find nl_termes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing nl_termes entity.
     *
     * @Route("/{id}/edit", name="nl_termes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArticleNewsletterBundle:nl_termes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find nl_termes entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a nl_termes entity.
    *
    * @param nl_termes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(nl_termes $entity)
    {
        $form = $this->createForm(new nl_termesType(), $entity, array(
            'action' => $this->generateUrl('nl_termes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing nl_termes entity.
     *
     * @Route("/{id}", name="nl_termes_update")
     * @Method("PUT")
     * @Template("ArticleNewsletterBundle:nl_termes:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArticleNewsletterBundle:nl_termes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find nl_termes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('nl_termes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a nl_termes entity.
     *
     * @Route("/{id}", name="nl_termes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ArticleNewsletterBundle:nl_termes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find nl_termes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('nl_termes'));
    }

    /**
     * Creates a form to delete a nl_termes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('nl_termes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
