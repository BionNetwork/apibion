<?php


namespace BiBundle\Service;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationStatus;
use BiBundle\Entity\Card;
use BiBundle\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;

class TestEntityFactory
{
    /** @var  EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createActivation()
    {
        $activation = new Activation();
        $activation->setCard($this->entityManager->getRepository(Card::class)->findOneBy([]));
        $activation->setUser($this->entityManager->getRepository(UserEntity::class)->findOneBy([]));
        $activation->setActivationStatus($this->entityManager->getRepository(ActivationStatus::class)
            ->findOneBy(['code' => ActivationStatus::STATUS_ACTIVE]));
        $this->entityManager->persist($activation);
        $this->entityManager->flush($activation);

        return $activation;
    }
}