<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Argument;
use BiBundle\Entity\ArgumentFilter;
use BiBundle\Entity\Card;
use BiBundle\Entity\CardRepresentation;
use BiBundle\Entity\File;
use BiBundle\Service\CardService;
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

    /**
     * @var
     */
    private $cardService;

    public function __construct(
        ArgumentTransferObject $argumentTransferObject,
        RepresentationTransferObject $representationTransferObject,
        HostBasedUrl $url,
        CardService $cardService)
    {
        $this->url = $url;
        $this->argumentTransferObject = $argumentTransferObject;
        $this->representationTransferObject = $representationTransferObject;
        $this->cardService = $cardService;
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

    /**
     * Get card's data normalized
     *
     * @param Card $card
     * @return Object\CardValueObject
     */
    public function getObjectData($card)
    {
        ### 8b188de9b1af75d8d2a83729b92cbc116390840a сломал CardRepository::findByFilter
        $card = $card instanceof Card ? $card : $this->cardService->findById($card['id']);
        ### поэтому так

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
            'image' => $card->getImageFile() ? $card->getImageFile()->getPath() : null,
            'category' => $card->getCardCategory() ? $card->getCardCategory()->getId() : null,
            'carousel' => array_map(function (File $file) {
                return $file->getPath();
            }, $this->cardService->getCarouselFiles($card)),
            'createdOn' => $card->getCreatedOn(),
            'arguments' => $this->getArgumentTransferObject()->getObjectListData($card->getArgument()),
            'argumentFilters' => $this->serializeArgumentFilters($card->getArgumentFilters()->toArray()),
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
     * @param ArgumentFilter[] $argumentFilters
     */
    private function serializeArgumentFilters(array $argumentFilters)
    {
        return array_map(
            function (ArgumentFilter $argumentFilter) {
                return [
                    'id' => $argumentFilter->getId(),
                    'label' => $argumentFilter->getLabel(),
                    'filter_control_type' => $argumentFilter->getFilterControlType() ? $argumentFilter->getFilterControlType()->getName() : null,
                    'argument_ids' => array_map(
                        function (Argument $argument) {
                            return $argument->getId();
                        },
                        $argumentFilter->getArguments()->toArray()),
                ];
            },
            $argumentFilters
        );
    }

    /**
     * @return RepresentationTransferObject
     */
    public function getRepresentationTransferObject()
    {
        return $this->representationTransferObject;
    }
}