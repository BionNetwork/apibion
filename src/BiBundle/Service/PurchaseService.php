<?php

namespace BiBundle\Service;

use BiBundle\BiBundle;
use BiBundle\Entity\User;
use BiBundle\Service\Exception\Purchase\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Card;
use BiBundle\Entity\Purchase;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @property EntityManager entityManager
 * @property User user
 * @property BackendService backendService
 */
class PurchaseService
{
    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage, BackendService $backendService)
    {
        $this->entityManager = $entityManager;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->backendService = $backendService;
    }

    /**
     * Saves purchase
     *
     * @param Purchase $purchase
     * @return Purchase|mixed
     */
    public function save(\BiBundle\Entity\Purchase $purchase)
    {
        $items = $this->user->getPurchase();
        $card = $purchase->getCard();

        // Прорверяем, нет ли карточки в перечне уже приобретенных
        foreach ($items as $purchase) {
            if($card->getId() === $purchase->getCard()->getId()) {
                return $purchase;
            }
        }

        $purchase->setUser($this->user);
        $purchase->setPrice($card->getPrice());

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        return $purchase;
    }

    /**
     * Создает экземпляр активации карточки
     *
     * @param \BiBundle\Entity\Purchase $purchase
     * @return \BiBundle\Entity\Activation
     */
    public function activate(\BiBundle\Entity\Purchase $purchase)
    {
        $activation = new \BiBundle\Entity\Activation();
        $activation->setCard($purchase->getCard());
        $activation->setUser($this->user);
        $activationStatus = $this->entityManager->getRepository('BiBundle:ActivationStatus')->findOneBy([
            'code' => \BiBundle\Entity\ActivationStatus::STATUS_PENDING
        ]);
        $activation->setActivationStatus($activationStatus);
        $this->entityManager->persist($activation);
        $this->entityManager->flush();
        try {
            $this->backendService->createActivation($activation);
        } catch (\Exception $e) {
            $this->entityManager->remove($activation);
            $this->entityManager->flush();
            throw $e;
        }

        return $activation;
    }

    /**
     * Получение карточек пользователя
     *
     * @param User $user
     * @return \BiBundle\Entity\Card[]
     */
    public function getUserCards(\BiBundle\Entity\User $user)
    {
        return $this->entityManager->getRepository('BiBundle:Purchase')->getUserCards($user);
    }

    /**
     *
     * @param Card $card
     * @param User $user
     * @return bool
     */
    public function isPurchased(Card $card, User $user)
    {
        foreach ($user->getPurchase() as $purchase) {
            /** @var Purchase $purchase */
            if($purchase->getCard() === $card) {
                return true;
            }
        }

        return false;
    }
}
