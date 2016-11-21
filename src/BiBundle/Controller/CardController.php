<?php

namespace BiBundle\Controller;

use BiBundle\Entity\Card;
use BiBundle\Entity\File;
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
    const CARD_CAROUSEL_IMAGE_PATH = 'images/cards';

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

        $uploadForm = $this->createForm('BiBundle\Form\CardCarouselFileType', []);
        $uploadForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($card);
            return $this->redirectToRoute('card_edit', ['id' => $card->getId()]);
        }

        $charts = $this->service->getCharts();
        $filters = $this->service->getFilters();

        return $this->render('@Bi/card/new.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
            'charts' => $charts,
            'filters' => $filters,
            'files' => [],
            'upload_form' => $uploadForm->createView()
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
        $deleteForm = $this->createDeleteForm($card);
        $editForm = $this->createForm('BiBundle\Form\CardType', $card);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->service->save($card);
            return $this->redirectToRoute('card_edit', ['id' => $card->getId()]);
        }

        $files = $this->service->getCarouselFiles($card);

        $uploadForm = $this->createForm('BiBundle\Form\CardCarouselFileType', $files);
        $uploadForm->handleRequest($request);

        $charts = $this->service->getCharts();
        $filters = $this->service->getFilters();

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {

            $fileService = $this->get('bi.file.service');

            $deletedFileIds = $uploadForm->get('deletedImages')->getData();
            if (!empty($deletedFileIds)) {
                /** @var File[] $files */
                foreach ($files as $file) {
                    if (in_array($file->getId(), $deletedFileIds)) {
                        foreach ($file->getCardImage() as $cardImage) {
                            $this->service->removeCarouselImage($cardImage);
                        }
                        $fileService->delete($file);
                    }
                }
            }

            /** @var UploadedFile[] $uploadedFiles */
            $uploadedFiles = $uploadForm->get('uploadedImages')->getData();
            if ($uploadedFiles !== [null]) {
                foreach ($uploadedFiles as $uploadedFile) {
                    $file = $fileService->upload($uploadedFile, self::CARD_CAROUSEL_IMAGE_PATH);
                    $this->service->createCarouselImage($card, $file);
                }
            }

            return $this->redirectToRoute('card_edit', ['id' => $card->getId()]);
        }

        return $this->render('@Bi/card/edit.html.twig', [
            'card' => $card,
            'files' => $files,
            'form' => $editForm->createView(),
            'charts' => $charts,
            'filters' => $filters,
            'delete_form' => $deleteForm->createView(),
            'upload_form' => $uploadForm->createView(),
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
