<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Card;
use BiBundle\Entity\User;
use BiBundle\Repository\CardRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class CardTransferObject
{

    /**
     * @var CardRepository
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

    public function __construct(CardRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get card's data normalized
     *
     * @param Card $card
     * @return array
     */
    public function getObjectData(Card $card)
    {
        $data = [
            'id' => $card->getId(),
            'name' => $card->getName(),
            'created_on' => $card->getCreatedOn(),
        ];
        $cardVO = Object\CardValueObject::fromArray($data, $this->url);
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
                'created_on' => !empty($card->getCreatedOn()) ? $card->getCreatedOn()->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * Get cards list
     *
     * @param array $data
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
     * @return DashboardRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}