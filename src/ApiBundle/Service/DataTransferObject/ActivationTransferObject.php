<?php

namespace ApiBundle\Service\DataTransferObject;

class ActivationTransferObject
{
    /**
     * @var CardTransferObject
     */
    private $cardTransferObjectService;

    public function __construct(CardTransferObject $cardTransferObject)
    {
        $this->cardTransferObjectService = $cardTransferObject;
    }

    /**
     * Get user's activated cards list
     *
     * @param \BiBundle\Entity\Activation[] $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $activation) {
            $item = [
                'id' => $activation->getId(),
                'created_on' => !empty($activation->getCreatedOn()) ? $activation->getCreatedOn()->getTimestamp() : null,
                'activation_status' => $activation->getActivationStatus()->getCode(),
                'card' => $this->cardTransferObjectService->getObjectData($activation->getCard())
            ];
            $result[] = $item;
        }
        return $result;
    }
}