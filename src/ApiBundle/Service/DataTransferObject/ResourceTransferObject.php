<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\User;
use BiBundle\Repository\ResourceRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class ResourceTransferObject
{

    /**
     * @var ResourceRepository
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

    public function __construct(ResourceRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get representation's data normalized
     *
     * @param \BiBundle\Entity\Resource $resource
     * @return array
     */
    public function getObjectData(\BiBundle\Entity\Resource $resource)
    {
        $data = [
            'id' => $resource->getId(),
            'activation_id' => !empty($resource->getActivation()) ? $resource->getActivation()->getId() : null,
            'path' => $resource->getPath(),
        ];
        return $data;
    }

    /**
     * Get representations list
     *
     * @param \BiBundle\Entity\Resource[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $resource) {
            $item = $this->getObjectData($resource);
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return ResourceRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}