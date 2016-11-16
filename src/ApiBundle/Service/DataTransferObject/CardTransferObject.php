<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Argument;
use BiBundle\Entity\ArgumentFilter;
use BiBundle\Entity\Card;
use BiBundle\Entity\CardChart;
use BiBundle\Entity\File;
use BiBundle\Service\CardCategoryService;
use BiBundle\Service\CardService;
use BiBundle\Service\Utils\HostBasedUrl;

class CardTransferObject
{
    const NO_CATEGORY_KEY = 'no_category';

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
     * @var CardService
     */
    private $cardService;
    /**
     * @var CardCategoryService
     */
    private $cardCategoryService;

    public function __construct(
        ArgumentTransferObject $argumentTransferObject,
        RepresentationTransferObject $representationTransferObject,
        HostBasedUrl $url,
        CardService $cardService,
        CardCategoryService $cardCategoryService)
    {
        $this->url = $url;
        $this->argumentTransferObject = $argumentTransferObject;
        $this->representationTransferObject = $representationTransferObject;
        $this->cardService = $cardService;
        $this->cardCategoryService = $cardCategoryService;
    }

    /**
     * Get cards list
     *
     * @param array $data
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
                $result[self::NO_CATEGORY_KEY] = $this->getObjectData($card);
            }
        }

        $categorizedCards = [];
        foreach ($result as $categoryId => $cards) {
            if (self::NO_CATEGORY_KEY !== $categoryId && $category = $this->cardCategoryService->findById($categoryId)) {
                $categorizedCards[$categoryId] = [
                    'category' => ['id' => $category->getId(), 'name' => $category->getName()],
                    'cards' => $cards
                ];
            } else {
                $categorizedCards[self::NO_CATEGORY_KEY] = ['cards' => $cards];
            }
        }
        return $categorizedCards;
    }

    /**
     * Get card's data normalized
     *
     * @param Card $card
     * @return Object\CardValueObject
     */
    public function getObjectData($card)
    {
        $card = $card instanceof Card ? $card : $this->cardService->findById($card['id']);

        $representations = $card->getCardChart();
        $representationsArray = [];
        /** @var CardChart $item */
        foreach ($representations as $item) {
            $representationsArray[] = $item->getChart();
        }
        $data = [
            'id' => $card->getId(),
            'name' => $card->getName(),
            'description' => $card->getDescription(),
            'descriptionLong' => $card->getDescriptionLong(),
            'rating' => $card->getRating(),
            'author' => $card->getAuthor(),
            'image' => $card->getImageFile() ? $card->getImageFile()->getPath() : null,
            'category' => $card->getCardCategory() ?
                ['id' => $card->getCardCategory()->getId(), 'name' => $card->getCardCategory()->getName()] : null,
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
     * @return array
     */
    private function serializeArgumentFilters(array $argumentFilters)
    {
        return array_map(
            function (ArgumentFilter $argumentFilter) {
                return [
                    'id' => $argumentFilter->getId(),
                    'label' => $argumentFilter->getLabel(),
                    'filter_type' => $argumentFilter->getFilterType() ? $argumentFilter->getFilterType()->getName() : null,
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