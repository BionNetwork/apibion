<?php

namespace BiBundle\Controller;

use BiBundle\Entity\Argument;
use BiBundle\Form\ArgumentType;
use BiBundle\Form\DeleteType;
use BiBundle\Service\ArgumentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArgumentController extends Controller
{
    /** @var  ArgumentService */
    private $service;

    public function __construct(ArgumentService $service)
    {
        $this->service = $service;
    }

    public function indexAction()
    {
        $arguments = $this->get('repository.argument_repository')->findAll();

        return $this->render('@Bi/argument/index.html.twig', ['arguments' => $arguments]);
    }

    public function newAction(Request $request)
    {
        $argument = new Argument();
        $form = $this->createForm(ArgumentType::class, $argument);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->service->create($argument);
            return $this->redirectToRoute('argument_index');
        }

        return $this->render('@Bi/argument/new.html.twig', [
            'argument' => $argument,
            'form' => $form->createView(),
        ]);
    }

    public function editAction(Argument $argument, Request $request)
    {
        $deleteForm = $this->createForm(DeleteType::class);
        $deleteForm->handleRequest($request);
        if ($deleteForm->isValid()) {
            throw new \ErrorException('Not implemented');
        }
        $editForm = $this->createForm(ArgumentType::class, $argument);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $this->service->save($argument);
            return $this->redirectToRoute('argument_index');
        }

        return $this->render('@Bi/argument/edit.html.twig', [
            'argument' => $argument,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
