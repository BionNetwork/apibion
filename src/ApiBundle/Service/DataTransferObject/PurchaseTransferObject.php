<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Purchase;

/**
 * @property CardTransferObject cardTransferObjectService
 */
class PurchaseTransferObject
{
    /**
     * PurchaseTransferObject constructor.
     * @param CardTransferObject $cardDtoService
     */
    public function __construct(CardTransferObject $cardDtoService)
    {
        $this->cardTransferObjectService = $cardDtoService;
    }

    /**
     * Get user's purchased cards list
     *
     * @param Purchase[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $purchase) {
            $item = [
                'id' => $purchase->getId(),
                'created_on' => $purchase->getCreatedOn(),
                'card' => $this->cardTransferObjectService->getObjectData($purchase->getCard()),
            ];
            $result[] = $item;
        }
        return $result;
    }
}