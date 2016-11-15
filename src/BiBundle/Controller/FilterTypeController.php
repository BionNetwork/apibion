<?php

namespace BiBundle\Controller;

use BiBundle\Entity\FilterType;
use BiBundle\Form\FilterTypeType;
use BiBundle\Service\FilterTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterTypeController extends Controller
{
    /**
     * @var FilterTypeService
     */
    private $service;

    /**
     * FilterTypeController constructor
     *
     * @param FilterTypeService $service
     */
    public function __construct(FilterTypeService $service)
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
        $types = $this->get('repository.filter_type_repository')->findAll();

        return $this->render('@Bi/filter-type/index.html.twig', ['types' => $types]);
    }

    /**
     * Creates new type
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $filterControlType = new FilterType();
        $form = $this->createForm(FilterTypeType::class, $filterControlType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->create($filterControlType);
            return $this->redirectToRoute('filter_control_type_index');
        }

        return $this->render('@Bi/filter-type/new.html.twig', [
            'type' => $filterControlType,
            'new_form' => $form->createView(),
        ]);
    }

    /**
     * Updates type
     *
     * @param FilterType $filterType
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(FilterType $filterType, Request $request)
    {
        $deleteForm = $this->createDeleteForm($filterType);
        $editForm = $this->createForm(FilterTypeType::class, $filterType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->service->update($filterType);
            return $this->redirectToRoute('filter_control_type_index');
        }

        return $this->render('@Bi/filter-type/edit.html.twig', [
            'type' => $filterType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes entity
     *
     * @param FilterType $filterType
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(FilterType $filterType, Request $request)
    {
        $form = $this->createDeleteForm($filterType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->delete($filterType);
        }

        return $this->redirectToRoute('filter_control_type_index');
    }

    /**
     * Creates a form to delete entity.
     *
     * @param FilterType $filterType
     * @return Form
     */
    private function createDeleteForm(FilterType $filterType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('filter_control_type_delete', array('id' => $filterType->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
