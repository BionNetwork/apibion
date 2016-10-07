<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardRepresentation;
use BiBundle\Service\Utils\HostBasedUrl;

class CardTransferObject
{
    /**
     * @var HostBasedUrl
     */
    private $url;
    /**
     * @var ArgumentTransferObject
     */
    private $argumentTransferObject;
    /**
     * @var RepresentationTransferObject
     */
    private $representationTransferObject;

    public function __construct(
        ArgumentTransferObject $argumentTransferObject,
        RepresentationTransferObject $representationTransferObject,
        HostBasedUrl $url)
    {
        $this->url = $url;
        $this->argumentTransferObject = $argumentTransferObject;
        $this->representationTransferObject = $representationTransferObject;
    }

    /**
     * Get card's data normalized
     *
     * @param Card $card
     * @return Object\CardValueObject
     */
    public function getObjectData(Card $card)
    {
        $representations = $card->getCardRepresentation();
        $representationsArray = [];
        /** @var CardRepresentation $item */
        foreach ($representations as $item) {
            $representationsArray[] = $item->getRepresentation();
        }
        $data = [
            'id' => $card->getId(),
            'name' => $card->getName(),
            'description' => $card->getDescription(),
            'descriptionLong' => $card->getDescriptionLong(),
            'rating' => $card->getRating(),
            'author' => $card->getAuthor(),
            'image' => $card->getImage(),
            'category' => $card->getCardCategory() ? $card->getCardCategory()->getId() : null,
            'carousel' => $card->getCarousel(),
            'createdOn' => $card->getCreatedOn(),
            'arguments' => $this->getArgumentTransferObject()->getObjectListData($card->getArgument()),
            'representations' => $this->getRepresentationTransferObject()->getObjectListData($representationsArray)
        ];

        return Object\CardValueObject::fromArray($data);
    }

    /**
     * Get cards list
     *
     * @param Card $card
     * @return array
     */
    public function getObjectListData(array $data)
    {
        return array_map([$this, 'getObjectData'], $data);
    }

    /**
     * @return ArgumentTransferObject
     */
    public function getArgumentTransferObject()
    {
        return $this->argumentTransferObject;
    }

    /**
     * @return RepresentationTransferObject
     */
    public function getRepresentationTransferObject()
    {
        return $this->representationTransferObject;
    }

    /**
     * Get categorized cards list
     *
     * @param \BiBundle\Entity\Card[] $data
     * @return array
     */
    public function getObjectListDataCategorized(array $data)
    {
        $result = [];

        foreach ($data as $card) {
            if ($card->getCardCategory()) {
                $result[$card->getCardCategory()->getId()][] = $this->getObjectData($card);
            } else {
                $result['no_category'] = $this->getObjectData($card);
            }
        }
        return $result;
    }
}