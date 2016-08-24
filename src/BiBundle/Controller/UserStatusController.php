<?php

namespace BiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BiBundle\Entity\UserStatus;
use BiBundle\Form\UserStatusType;

/**
 * UserStatus controller.
 *
 */
class UserStatusController extends Controller
{
    /**
     * Lists all UserStatus entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userStatuses = $em->getRepository('BiBundle:UserStatus')->findAll();

        return $this->render('BiBundle:userstatus:index.html.twig', array(
            'userStatuses' => $userStatuses,
        ));
    }

    /**
     * Creates a new UserStatus entity.
     *
     */
    public function newAction(Request $request)
    {
        $userStatus = new UserStatus();
        $form = $this->createForm('BiBundle\Form\UserStatusType', $userStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userStatus);
            $em->flush();

            return $this->redirectToRoute('user_status_show', array('id' => $userStatus->getId()));
        }

        return $this->render('BiBundle:userstatus:new.html.twig', array(
            'userStatus' => $userStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserStatus entity.
     *
     */
    public function showAction(UserStatus $userStatus)
    {
        $deleteForm = $this->createDeleteForm($userStatus);

        return $this->render('BiBundle:userstatus:show.html.twig', array(
            'userStatus' => $userStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserStatus entity.
     *
     */
    public function editAction(Request $request, UserStatus $userStatus)
    {
        $deleteForm = $this->createDeleteForm($userStatus);
        $editForm = $this->createForm('BiBundle\Form\UserStatusType', $userStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userStatus);
            $em->flush();

            return $this->redirectToRoute('user_status_edit', array('id' => $userStatus->getId()));
        }

        return $this->render('BiBundle:userstatus:edit.html.twig', array(
            'userStatus' => $userStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserStatus entity.
     *
     */
    public function deleteAction(Request $request, UserStatus $userStatus)
    {
        $form = $this->createDeleteForm($userStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userStatus);
            $em->flush();
        }

        return $this->redirectToRoute('user_status_index');
    }

    /**
     * Creates a form to delete a UserStatus entity.
     *
     * @param UserStatus $userStatus The UserStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserStatus $userStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_status_delete', array('id' => $userStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
