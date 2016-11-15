<?php

namespace BiBundle\Service;

use BiBundle\Entity\FilterType;
use BiBundle\Repository\FilterTypeRepository;
use Doctrine\ORM\EntityManager;

class FilterTypeService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var FilterTypeRepository
     */
    private $repository;

    public function __construct(EntityManager $entityManager, FilterTypeRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function create(FilterType $filterType)
    {
        $filterType->setSort($this->getRepository()->nextSortValue());
        $this->getEm()->persist($filterType);
        $this->getEm()->flush($filterType);
    }

    public function update(FilterType $filterType)
    {
        $this->getEm()->flush($filterType);
    }

    public function delete(FilterType $filterType)
    {
        $this->getEm()->remove($filterType);
        $this->getEm()->flush($filterType);
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->entityManager;
    }

    /**
     * @return FilterTypeRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}