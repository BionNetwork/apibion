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
     * @return ArgumentTransferObject
     */
    public function getArgumentTransferObject()
    {
        return $this->argumentTransferObject;
    }

    /**
     * Get cards list
     *
     * @param array $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $card) {
            $item = [
                'id' => $card['id'],
                'name' => $card['name'],
                'description' => $card['description'],
                'description_long' => $card['description_long'],
                'category' => $card['category_id'],
                'rating' => $card['rating'],
                'price' => $card['price'],
                'purchased' => !empty($card['purchase_id']),
                'carousel' => !empty($card['carousel']) ? explode(';', $card['carousel']) : [],
                'created_on' => !empty($card['created_on']) ? $card['created_on']->getTimestamp() : null,
                'updated_on' => !empty($card['updated_on']) ? $card['updated_on']->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
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