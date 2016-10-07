<?php

namespace BiBundle\Controller;

use BiBundle\Entity\FilterControlType;
use BiBundle\Form\FilterControlTypeType;
use BiBundle\Service\FilterControlTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterControlTypeController extends Controller
{
    /**
     * @var FilterControlTypeService
     */
    private $service;

    /**
     * FilterControlTypeController constructor
     *
     * @param FilterControlTypeService $service
     */
    public function __construct(FilterControlTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * Shows list of types
     *
     * @return Response
     */
    public function indexAction()
    {
        $types = $this->get('repository.filter_control_type_repository')->findAll();

        return $this->render('@Bi/filter-control-type/index.html.twig', ['types' => $types]);
    }

    /**
     * Creates new type
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $filterControlType = new FilterControlType();
        $form = $this->createForm(FilterControlTypeType::class, $filterControlType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->create($filterControlType);
            return $this->redirectToRoute('filter_control_type_index');
        }

        return $this->render('@Bi/filter-control-type/new.html.twig', [
            'type' => $filterControlType,
            'new_form' => $form->createView(),
        ]);
    }

    /**
     * Updates type
     *
     * @param FilterControlType $filterControlType
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(FilterControlType $filterControlType, Request $request)
    {
        $deleteForm = $this->createDeleteForm($filterControlType);
        $editForm = $this->createForm(FilterControlTypeType::class, $filterControlType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->service->update($filterControlType);
            return $this->redirectToRoute('filter_control_type_index');
        }

        return $this->render('@Bi/filter-control-type/edit.html.twig', [
            'type' => $filterControlType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a FilterControlType entity
     *
     * @param FilterControlType $filterControlType
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(FilterControlType $filterControlType, Request $request)
    {
        $form = $this->createDeleteForm($filterControlType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->delete($filterControlType);
        }

        return $this->redirectToRoute('filter_control_type_index');
    }

    /**
     * Creates a form to delete a FilterControlType entity.
     *
     * @param FilterControlType $filterControlType
     * @return Form
     */
    private function createDeleteForm(FilterControlType $filterControlType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('filter_control_type_delete', array('id' => $filterControlType->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
