<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use BiBundle\Service\Backend\Gateway\Exception;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
            try {
                $this->uploadData($resource);
            } catch (Exception $ex) {
                throw new \RuntimeException($ex->getMessage());
            }
        }

        $em->persist($resource);
        $em->flush();
        return $resource;
    }

    /**
     * Uploads data to remote host
     *
     * @param \BiBundle\Entity\Resource $resource
     * @throws Backend\Exception
     */
    protected function uploadData(\BiBundle\Entity\Resource $resource)
    {
        $backendService = $this->container->get('bi.backend.service');
        $backendService->putResource($resource);
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