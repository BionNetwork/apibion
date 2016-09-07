<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Argument;
use BiBundle\Entity\User;
use BiBundle\Repository\ArgumentRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class ArgumentTransferObject
{

    /**
     * @var ArgumentRepository
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

    public function __construct(ArgumentRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get argument's data normalized
     *
     * @param Argument $argument
     * @return array
     */
    public function getObjectData(Argument $argument)
    {
        $data = [
            'name' => $argument->getName(),
            'code' => $argument->getCode(),
            'dimension' => $argument->getDimension()
        ];
        return $data;
    }

    /**
     * Get argument list
     *
     * @param \BiBundle\Entity\Argument[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $argument) {
            $item = $this->getObjectData($argument);
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return ArgumentRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}