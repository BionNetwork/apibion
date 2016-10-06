<?php

namespace BiBundle\Service;

use BiBundle\BiBundle;
use BiBundle\Service\Exception\Purchase\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Card;
use BiBundle\Entity\Purchase;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PurchaseService extends UserAwareService
{
    /**
     * Saves purchase
     *
     * @param Purchase $purchase
     * @return Purchase|mixed
     */
    public function save(\BiBundle\Entity\Purchase $purchase)
    {
        $em = $this->getEntityManager();

        $items = $this->getUser()->getPurchase();
        $card = $purchase->getCard();

        // Прорверяем, нет ли карточки в перечне уже приобретенных
        foreach ($items as $purchase) {
            if($card->getId() === $purchase->getCard()->getId()) {
                return $purchase;
            }
        }

        $purchase->setUser($this->getUser());
        $purchase->setPrice($card->getPrice());

        $em->persist($purchase);
        $em->flush();

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
        $em = $this->getEntityManager();

        $activation = new \BiBundle\Entity\Activation();

        $activation->setCard($purchase->getCard());
        $activation->setUser($this->getUser());

        $activationStatus = $em->getRepository('BiBundle:ActivationStatus')->findOneBy([
            'code' => \BiBundle\Entity\ActivationStatus::STATUS_PENDING
        ]);

        $activation->setActivationStatus($activationStatus);

        $em->persist($activation);
        $em->flush();

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
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Purchase')->getUserCards($user);
    }
}
