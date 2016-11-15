<?php

namespace BiBundle\Service;

use BiBundle\Entity\FilterType;
use Doctrine\ORM\EntityManager;

class FilterTypeService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(FilterType $filterControlType)
    {
        $this->entityManager->persist($filterControlType);
        $this->entityManager->flush($filterControlType);
    }

    public function update(FilterType $filterControlType)
    {
        $this->entityManager->flush($filterControlType);
    }

    public function delete(FilterType $filterControlType)
    {
        $this->entityManager->remove($filterControlType);
        $this->entityManager->flush($filterControlType);
    }
}