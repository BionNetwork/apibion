<?php

namespace BiBundle\Service;

use BiBundle\Entity\FilterControlType;
use Doctrine\ORM\EntityManager;

class FilterControlTypeService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(FilterControlType $filterControlType)
    {
        $this->entityManager->persist($filterControlType);
        $this->entityManager->flush($filterControlType);
    }

    public function update(FilterControlType $filterControlType)
    {
        $this->entityManager->flush($filterControlType);
    }

    public function delete(FilterControlType $filterControlType)
    {
        $this->entityManager->remove($filterControlType);
        $this->entityManager->flush($filterControlType);
    }
}