<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Representation;
use BiBundle\Entity\User;
use BiBundle\Repository\RepresentationRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class RepresentationTransferObject
{

    /**
     * @var RepresentationRepository
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

    public function __construct(RepresentationRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get representation's data normalized
     *
     * @param Representation $representation
     * @return array
     */
    public function getObjectData(Representation $representation)
    {
        $data = [
            'id' => $representation->getId(),
            'name' => $representation->getName(),
            'code' => $representation->getCode(),
        ];
        return $data;
    }

    /**
     * Get representations list
     *
     * @param \BiBundle\Entity\Representation[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $representation) {
            $item = $this->getObjectData($representation);
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