<?php


namespace BiBundle\Service;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationStatus;
use BiBundle\Entity\Card;
use BiBundle\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;

class TestEntityFactory
{
    /** @var  EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createActivation(UserEntity $user = null)
    {
        $activation = new Activation();
        $activation->setCard($this->entityManager->getRepository(Card::class)->findOneBy([]));
        $activation->setUser(
            is_null($user) ? $this->entityManager->getRepository(UserEntity::class)->findOneBy([]) : $user
        );
        $activation->setActivationStatus($this->entityManager->getRepository(ActivationStatus::class)
            ->findOneBy(['code' => ActivationStatus::STATUS_ACTIVE]));
        $this->entityManager->persist($activation);
        $this->entityManager->flush($activation);

        return $activation;
    }

    /**
     * @param $entity
     * @return void
     */
    public function refreshEntity($entity)
    {
        if ($this->entityManager->getUnitOfWork()->getEntityState($entity) !== UnitOfWork::STATE_MANAGED) {
            $entity = $this->entityManager->getRepository(get_class($entity))->find($entity->getId());
        }
        $this->entityManager->refresh($entity);
    }

    /**
     * @param $entities array
     * @return void
     */
    public function refreshEntities(array $entities)
    {
        foreach ($entities as $entity) {
            $this->refreshEntity($entity);
        }
    }
}