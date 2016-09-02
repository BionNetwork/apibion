<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Purchase;
use BiBundle\Entity\User;
use BiBundle\Repository\PurchaseRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class PurchaseTransferObject
{

    /**
     * @var PurchaseRepository
     */
    private $repository;

    /**
     * @var HostBasedUrl
     */
    private $url;

    /**
     * @var User
     */
    private $user;

    public function __construct(PurchaseRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get card's data normalized
     *
     * @param Purchase $purchase
     * @return array
     */
    public function getObjectData(Purchase $purchase)
    {
        $data = [
            'id' => $purchase->getId(),
            'name' => $purchase->getName(),
            'created_on' => $purchase->getCreatedOn(),
            'card' => $this->getCard($purchase->getCard()),
        ];
        $purchaseVO = Object\PurchaseValueObject::fromArray($data, $this->url);
        return $purchaseVO;
    }

    /**
     * Get user's purchased cards list
     *
     * @param array $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $purchase) {
            $item = [
                'id' => $purchase->getId(),
                'created_on' => $purchase->getCreatedOn(),
                'card' => $this->getCard($purchase->getCard()),
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * Get user's purchased cards list
     *
     * @param Purchase[] $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $card) {
            $item = [
                'id' => $card['id'],
                'name' => $card['name'],
                'created_on' => !empty($card['created_on']) ? $card['created_on']->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return PurchasedRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * @param Card|null $card
     * @return array|null
     */
    protected function getCard(\BiBundle\Entity\Card $card = null)
    {
        $result = null;
        if ($card) {
            $result = [
                'id' => $card->getId(),
                'name' => $card->getName(),
                'type' => $card->getType(),
            ];
        }
        return $result;
    }
}