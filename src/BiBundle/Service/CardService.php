<?php

namespace BiBundle\Service;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCarouselImage;
use BiBundle\Entity\File;
use Doctrine\ORM\EntityManager;

class CardService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns cards by filter
     *
     * @param \BiBundle\Entity\Filter\Card $filter
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        return $this->entityManager->getRepository('BiBundle:Card')->findByFilter($filter);
    }

    /**
     * Returns all cards with data from related tables
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getAllCards()
    {
        return $this->entityManager->getRepository('BiBundle:Card')->findAllCards();
    }

    /**
     * @param Card $card
     */
    public function create(Card $card)
    {
        $this->entityManager->persist($card);
        $this->entityManager->flush($card);
    }

    /**
     *
     */
    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param Card $card
     */
    public function delete(Card $card)
    {
        $this->entityManager->remove($card);
        $this->entityManager->flush($card);
    }

    /**
     * @param Card $card
     * @return File[]
     */
    public function getCarouselFiles(Card $card)
    {
        return $this->entityManager->getRepository('BiBundle:Card')->findCarouselFiles($card);
    }

    /**
     * @param Card $card
     * @param File $file
     * @return CardCarouselImage
     */
    public function createCarouselImage(Card $card, File $file)
    {
        $cardCarouselImage = new CardCarouselImage();
        $cardCarouselImage->setCard($card);
        $cardCarouselImage->setFile($file);
        $this->entityManager->persist($cardCarouselImage);
        $this->entityManager->flush($cardCarouselImage);

        return $cardCarouselImage;
    }

    /**
     * @param CardCarouselImage $cardCarouselImage
     */
    public function removeCarouselImage(CardCarouselImage $cardCarouselImage)
    {
        $this->entityManager->remove($cardCarouselImage);
        $this->entityManager->flush($cardCarouselImage);
    }
}
