<?php

namespace BiBundle\Service;

use BiBundle\Entity\Argument;
use Doctrine\ORM\EntityManager;

class ArgumentService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Argument $argument)
    {
        $this->entityManager->flush($argument);
    }

    public function create(Argument $argument)
    {
        $this->entityManager->persist($argument);
        $this->entityManager->flush($argument);
    }
}