<?php

namespace BiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BiBundle\Entity\UserRole;
use BiBundle\Form\UserRoleType;

/**
 * UserRole controller.
 *
 */
class UserRoleController extends Controller
{
    /**
     * Lists all UserRole entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userRoles = $em->getRepository('BiBundle:UserRole')->findAll();

        return $this->render('BiBundle:userrole:index.html.twig', array(
            'userRoles' => $userRoles,
        ));
    }

    /**
     * Creates a new UserRole entity.
     *
     */
    public function newAction(Request $request)
    {
        $userRole = new UserRole();
        $form = $this->createForm('BiBundle\Form\UserRoleType', $userRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRole);
            $em->flush();

            return $this->redirectToRoute('user_role_show', array('id' => $userRole->getId()));
        }

        return $this->render('BiBundle:userrole:new.html.twig', array(
            'userRole' => $userRole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserRole entity.
     *
     */
    public function showAction(UserRole $userRole)
    {
        $deleteForm = $this->createDeleteForm($userRole);

        return $this->render('BiBundle:userrole:show.html.twig', array(
            'userRole' => $userRole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserRole entity.
     *
     */
    public function editAction(Request $request, UserRole $userRole)
    {
        $deleteForm = $this->createDeleteForm($userRole);
        $editForm = $this->createForm('BiBundle\Form\UserRoleType', $userRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRole);
            $em->flush();

            return $this->redirectToRoute('user_role_edit', array('id' => $userRole->getId()));
        }

        return $this->render('BiBundle:userrole:edit.html.twig', array(
            'userRole' => $userRole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserRole entity.
     *
     */
    public function deleteAction(Request $request, UserRole $userRole)
    {
        $form = $this->createDeleteForm($userRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userRole);
            $em->flush();
        }

        return $this->redirectToRoute('user_role_index');
    }

    /**
     * Creates a form to delete a UserRole entity.
     *
     * @param UserRole $userRole The UserRole entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserRole $userRole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_role_delete', array('id' => $userRole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
