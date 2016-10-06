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
     * @return array
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
            'carousel' => $card->getCarousel(),
            'createdOn' => $card->getCreatedOn(),
            'arguments' => $this->getArgumentTransferObject()->getObjectListData($card->getArgument()),
            'representations' => $this->getRepresentationTransferObject()->getObjectListData($representationsArray)
        ];
        $cardVO = Object\CardValueObject::fromArray($data);
        return $cardVO;
    }

    /**
     * Get cards list
     *
     * @param \BiBundle\Entity\Card[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $card) {
            $item = [
                'id' => $card->getId(),
                'name' => $card->getName(),
                'description' => $card->getDescription(),
                'description_long' => $card->getDescriptionLong(),
                'rating' => $card->getRating(),
                'price' => $card->getPrice(),
                'carousel' => !empty($card->getCarousel()) ? explode(';', $card->getCarousel()) : [],
                'created_on' => !empty($card->getCreatedOn()) ? $card->getCreatedOn()->getTimestamp() : null,
                'updated_on' => !empty($card->getUpdatedOn()) ? $card->getUpdatedOn()->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
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
}