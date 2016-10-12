<?php

namespace BiBundle\Service;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCarouselImage;
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
     * @param Card $card
     */
    public function update(Card $card)
    {
        $this->entityManager->flush($card);
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
     * @return array
     */
    public function getCarouselFiles(Card $card)
    {
        return $this->entityManager->getRepository('BiBundle:Card')->findCarouselFiles($card);
    }

    /**
     * @param CardCarouselImage $cardCarouselImage
     * @return array
     */
    public function addCarouselFile(CardCarouselImage $cardCarouselImage)
    {
        $this->entityManager->persist($cardCarouselImage);
        $this->entityManager->flush($cardCarouselImage);
    }

    /**
     * @param CardCarouselImage $cardCarouselImage
     * @return array
     */
    public function removeCarouselFile(CardCarouselImage $cardCarouselImage)
    {
        $this->entityManager->remove($cardCarouselImage);
        $this->entityManager->flush($cardCarouselImage);
    }
}
