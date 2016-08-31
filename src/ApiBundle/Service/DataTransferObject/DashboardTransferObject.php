<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Dashboard;
use BiBundle\Entity\User;
use BiBundle\Repository\DashboardRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class DashboardTransferObject
{

    /**
     * @var DashboardRepository
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

    public function __construct(DashboardRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get dashboard's data normalized
     *
     * @param Dashboard $dashboard
     * @return array
     */
    public function getObjectData(Dashboard $dashboard)
    {
        $data = [
            'id' => $dashboard->getId(),
            'name' => $dashboard->getName(),
            'created_on' => $dashboard->getCreatedOn(),
        ];
        $dashboardVO = Object\DashboardValueObject::fromArray($data, $this->url);
        return $dashboardVO;
    }

    /**
     * Get dashboards list
     *
     * @param array $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $dashboard) {
            $item = [
                'id' => $dashboard->getId(),
                'name' => $dashboard->getName(),
                'created_on' => !empty($dashboard->getCreatedOn()) ? $dashboard->getCreatedOn()->getTimestamp() : null,
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