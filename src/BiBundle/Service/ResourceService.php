<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use BiBundle\Service\Backend\Gateway\Exception;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Dashboard service
 */
class ResourceService extends UserAwareService
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setServiceContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Save resource
     *
     * @param \BiBundle\Entity\Resource $resource
     * @return \BiBundle\Entity\Resource|int
     */
    public function save(\BiBundle\Entity\Resource $resource)
    {
        $em = $this->getEntityManager();

        if ($resource->getId() === null) {
            $resource->setCreatedOn(new \DateTime());
            try {
                $backendService = $this->container->get('bi.backend.service');
                $resource = $backendService->putResource($resource);
            } catch (Exception $ex) {
                throw new \Symfony\Component\HttpKernel\Exception\HttpException($ex->getMessage());
            }
        }

        $em->persist($resource);
        $em->flush();
        return $resource;
    }

    /**
     * Возвращает источники данных по фильтру
     *
     * @param \BiBundle\Entity\Filter\Resource $filter
     *
     * @return \BiBundle\Entity\Resource[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Resource $filter)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Resource')->findByFilter($filter);
    }
}