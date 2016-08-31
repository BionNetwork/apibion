<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Backend service
 */
class BackendService extends UserAwareService
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Получение источника с сервера BI
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return int
     */
    public function getResource(\BiBundle\Entity\Resource $resource)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);
        $client->setMethod('datasources/%1$d/');
        $client->setMethodParams(['id' => $resource->getPlatformId()]);

    }

    /**
     * Отправка источника данных на сервер BI
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return int
     */
    public function putResource(\BiBundle\Entity\Resource $resource)
    {

    }

}