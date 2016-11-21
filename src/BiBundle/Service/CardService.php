<?php

namespace BiBundle\Service;

use BiBundle\Entity\Argument;
use BiBundle\Entity\Card;
use BiBundle\Entity\CardImage;
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
     * @param $id
     * @return Argument|null|object
     */
    protected function getArgument($id)
    {
        return $this->getEm()->getRepository('BiBundle:Argument')->find($id);
    }
    /**
     * @param Card $card
     */
    public function save(Card $card)
    {
        /** @var Argument $argument */
        $arguments = $card->getArgument();

        foreach ($arguments as $argument) {
            if (null === $argument->getId()) {
                $argument->setCard($card);
                $this->getEm()->persist($argument);
            }
        }
        $this->cleanArguments($card);

        $this->getEm()->persist($card);
        $this->getEm()->flush();
    }

    /**
     * Удаление аргументов, не привязанных более к карточке
     *
     * @param Card $card
     */
    private function cleanArguments(Card $card)
    {
        $em = $this->getEm();

        // Удаление целей проекта, которые более не привязаны к проекту
        $goalRepo = $em->getRepository('BiBundle:Argument');
        $arguments = $goalRepo->findBy(['card' => $card]);
        $currentArguments = $card->getArgument();
        $names = [];
        foreach ($currentArguments as $argument) {
            $names[] = $argument->getName();
        }
        foreach ($arguments as $argument) {
            if (!in_array($argument->getName(), $names)) {
                $em->remove($argument);
            }
        }
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
     * @return CardImage
     */
    public function createCarouselImage(Card $card, File $file)
    {
        $image = new CardImage();
        $image->setCard($card);
        $image->setFile($file);
        $this->getEm()->persist($image);
        $this->getEm()->flush($image);

        return $image;
    }

    /**
     * @param CardImage $cardImage
     */
    public function removeCarouselImage(CardImage $cardImage)
    {
        $this->getEm()->remove($cardImage);
        $this->getEm()->flush($cardImage);
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
