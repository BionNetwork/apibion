<?php

namespace BiBundle\Controller;

use BiBundle\Entity\Card;
use BiBundle\Form\CardType;
use BiBundle\Service\CardService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Card controller.
 *
 */
class CardController extends Controller
{
    const CARDS_PREVIEW_PATH = '/images/cards-preview';

    /**
     * @var CardService
     */
    private $service;

    /**
     * CardController constructor
     *
     * @param CardService $service
     */
    public function __construct(CardService $service)
    {
        $this->service = $service;
    }

    /**
     * Lists all Card entities.
     */
    public function indexAction()
    {
        $cards = $this->get('repository.card_repository')->findBy([], ['id' => 'asc']);
        return $this->render('@Bi/card/index.html.twig', ['cards' => $cards]);
    }

    /**
     * Creates a new Card entity.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($card->getImage() instanceof UploadedFile) {

                /** @var UploadedFile $file */
                $file = $card->getImage();
                $uploadResource = $this->get('file.upload_resource');
                $uploadResource->setUploadPath(self::CARDS_PREVIEW_PATH);
                $card->setImage(
                    $uploadResource->upload($file)['path']
                );
            }

            $this->service->create($card);
            return $this->redirectToRoute('card_index');
        }

        return $this->render('@Bi/card/new.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Card entity.
     *
     * @param Request $request
     * @param Card $card
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Card $card)
    {
//        if (!empty($card->getImage()) && is_string($card->getImage())) {
//            $card->setImage(
//                new File($this->getParameter('upload_dir') . '/' . $card->getImage())
//            );
//        }
        $deleteForm = $this->createDeleteForm($card);
        $editForm = $this->createForm('BiBundle\Form\CardType', $card);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($card->getImage() instanceof UploadedFile) {

                /** @var UploadedFile $file */
                $file = $card->getImage();
                $uploadResource = $this->get('file.upload_resource');
                $uploadResource->setUploadPath(self::CARDS_PREVIEW_PATH);
                $card->setImage(
                    $uploadResource->upload($file)['path']
                );
            }

            $this->service->update($card);
            return $this->redirectToRoute('card_index', ['id' => $card->getId()]);
        }

        $arguments = $card->getArgument();

        return $this->render('@Bi/card/edit.html.twig', [
            'card' => $card,
            'arguments' => $arguments,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Card entity.
     *
     * @param Request $request
     * @param Card $card
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Card $card)
    {
        $form = $this->createDeleteForm($card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->delete($card);
        }

        return $this->redirectToRoute('card_index');
    }

    /**
     * Creates a form to delete a Card entity.
     *
     * @param Card $card The Card entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Card $card)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('card_delete', array('id' => $card->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
