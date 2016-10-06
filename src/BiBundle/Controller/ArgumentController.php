<?php

namespace BiBundle\Controller;

use BiBundle\Entity\Argument;
use BiBundle\Form\ArgumentType;
use BiBundle\Form\DeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArgumentController extends Controller
{
    public function indexAction()
    {
        $arguments = $this->get('repository.argument_repository')->findAll();

        return $this->render('@Bi/argument/index.html.twig', ['arguments' => $arguments]);
    }

    public function editAction(Argument $argument, Request $request)
    {
        $deleteForm = $this->createForm(DeleteType::class);
        $deleteForm->handleRequest($request);
        if ($deleteForm->isValid()) {
            throw new \Exception('delete');
        }
        $editForm = $this->createForm(ArgumentType::class, $argument);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            throw new \Exception('save');
        }

        return $this->render('@Bi/argument/edit.html.twig', [
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
}
