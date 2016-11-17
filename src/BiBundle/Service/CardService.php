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
        return $this->getEm()->getRepository('BiBundle:Card')->findByFilter($filter);
    }

    /**
     * Returns all cards with data from related tables
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getAllCards()
    {
        return $this->getEm()->getRepository('BiBundle:Card')->findAllCards();
    }

    /**
     * @param Card $card
     */
    public function save(Card $card)
    {
        $this->getEm()->persist($card);
        $this->getEm()->flush();
    }

    /**
     * @param Card $card
     */
    public function delete(Card $card)
    {
        $this->getEm()->remove($card);
        $this->getEm()->flush($card);
    }

    /**
     * @param Card $card
     * @return File[]
     */
    public function getCarouselFiles(Card $card)
    {
        return $this->getEm()->getRepository('BiBundle:Card')->findCarouselFiles($card);
    }

    /**
     * @return array|\BiBundle\Entity\Chart[]
     */
    public function getCharts()
    {
        return $this->getEm()->getRepository('BiBundle:Chart')->findAll();
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
        $this->getEm()->persist($cardCarouselImage);
        $this->getEm()->flush($cardCarouselImage);

        return $cardCarouselImage;
    }

    /**
     * @param CardCarouselImage $cardCarouselImage
     */
    public function removeCarouselImage(CardCarouselImage $cardCarouselImage)
    {
        $this->getEm()->remove($cardCarouselImage);
        $this->getEm()->flush($cardCarouselImage);
    }

    /**
     * @param $id
     * @return Card|null
     */
    public function findById($id)
    {
        return $this->getEm()->getRepository('BiBundle:Card')->find($id);
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->entityManager;
    }

    /**
     * @return array|\BiBundle\Entity\FilterType[]
     */
    public function getFilters()
    {
        return $this->getEm()->getRepository('BiBundle:FilterType')->findBy([], ['sort' => 'ASC']);
    }
}
