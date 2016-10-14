<?php


namespace BiBundle\Service;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Entity\ActivationStatus;
use BiBundle\Entity\Card;
use BiBundle\Entity\Purchase;
use BiBundle\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;

class TestEntityFactory
{
    /** @var  EntityManager */
    private $entityManager;

    private $purgeAllowedClasses = [
        ActivationSetting::class,
        Activation::class,
        Purchase::class,
    ];

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

    /**
     * Remove all records from given entity table
     *
     * @param $class
     * @throws \Exception
     */
    public function purgeTestEntitiesClass($class)
    {
        if (in_array($class, $this->purgeAllowedClasses)) {
            $this->entityManager->createQuery("DELETE FROM $class")->execute();
        } else {
            throw new \Exception("Purging $class is not allowed");
        }
    }

    /**
     * Remove all records from given entities tables
     *
     * @param $class
     * @throws \Exception
     */
    public function purgeTestEntities(array $classes)
    {
        if ($notAllowedClasses = array_diff($classes, $this->purgeAllowedClasses)) {
            throw new \Exception('Purging not allowed for classes: ' . implode(', ', $notAllowedClasses));
        }
        foreach ($this->purgeAllowedClasses as $class) {
            if (in_array($class, $classes)) {
                $this->purgeTestEntitiesClass($class);
            }
        }
    }

    /**
     * @return Purchase
     */
    public function createPurchase(UserEntity $user)
    {
        $purchase = new Purchase();
        $card = $this->entityManager->getRepository(Card::class)->findOneBy([]);
        $purchase->setCard($card);
        $purchase->setUser($user);
        $purchase->setPrice($card->getPrice());

        $this->entityManager->persist($purchase);
        $this->entityManager->flush($purchase);

        return $purchase;
    }
}