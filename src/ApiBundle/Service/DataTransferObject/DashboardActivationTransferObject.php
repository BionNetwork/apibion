<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\DashboardActivation;
use BiBundle\Entity\User;
use BiBundle\Repository\DashboardActivationRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class DashboardActivationTransferObject
{

    /**
     * @var DashboardActivationRepository
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

    public function __construct(DashboardActivationRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get dashboard activation's data normalized
     *
     * @param DashboardActivation $dashboardActivation
     * @return array
     */
    public function getObjectData(DashboardActivation $dashboardActivation)
    {
        $data = [
            'id' => $dashboardActivation->getId(),
            'name' => $dashboardActivation->getName(),
            'created_on' => $dashboardActivation->getCreatedOn(),
        ];
        return $data;
    }

    /**
     * Get dashboard activations list
     *
     * @param array $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $dashboardActivation) {
            $item = [
                'id' => $dashboardActivation['id'],
                'name' => $dashboardActivation['name'],
                'created_on' => !empty($dashboardActivation['created_on']) ? $dashboardActivation['created_on']->getTimestamp() : null,
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return DashboardActivationRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}