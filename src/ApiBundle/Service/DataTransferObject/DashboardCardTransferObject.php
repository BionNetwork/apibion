<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\DashboardCard;
use BiBundle\Entity\User;
use BiBundle\Repository\DashboardCardRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class DashboardCardTransferObject
{

    /**
     * @var DashboardCardRepository
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

    public function __construct(DashboardCardRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get dashboard card's data normalized
     *
     * @param DashboardCard $dashboardCard
     * @return array
     */
    public function getObjectData(DashboardCard $dashboardCard)
    {
        $data = [
            'id' => $dashboardCard->getId(),
            'name' => $dashboardCard->getName(),
            'created_on' => $dashboardCard->getCreatedOn(),
        ];
        $dashboardCardVO = Object\DasboardCardValueObject::fromArray($data, $this->url);
        return $dashboardCardVO;
    }

    /**
     * Get dashboard cards list
     *
     * @param array $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $dashboardCard) {
            $item = [
                'id' => $dashboardCard['id'],
                'name' => $dashboardCard['name'],
                'created_on' => !empty($dashboardCard['created_on']) ? $dashboardCard['created_on']->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return DashboardCardRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}