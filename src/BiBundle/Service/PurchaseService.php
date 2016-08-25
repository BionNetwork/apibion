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
     * Purchase card from store
     *
     * @param Card $card
     */
    public function purchase(Card $card)
    {
        $em = $this->getEntityManager();

        // Прорверяем, нет ли карточки в перечне уже приобретенных
        $purchased = false;
        $items = $this->getUser()->getPurchase();
        foreach ($items as $purchase) {
            if($card->getId() === $purchase->getCard()->getId()) {
                $purchased = true;
            }
        }
        if($purchased) {
            throw new AlreadyPurchasedException('Уже приобретенная карточка');
        }

        $purchase = new \BiBundle\Entity\Purchase();

        $purchase->setCard($card);
        $purchase->setUser($this->getUser());
        $purchase->setPrice($card->getPrice());
        $purchase->setCreatedOn(new \DateTime());

        $em->persist($purchase);
        $em->flush();

        return $purchase;
    }


    /**
     * Создает экземпляр активации карточки
     *
     * @param \BiBundle\Entity\Purchase $purchase
     */
    public function activate(\BiBundle\Entity\Purchase $purchase)
    {
        $em = $this->getEntityManager();

        $activation = new \BiBundle\Entity\Activation();

        $activation->setCard($purchase->getCard());
        $activation->setUser($this->getUser());
        $activation->setCreatedOn(new \DateTime());

        $em->persist($activation);
        $em->flush();

        return $activation;
    }

    /**
     * Получение карточек пользователя
     *
     * @param User $user
     */
    public function getUserCards(\BiBundle\Entity\User $user)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Purchase')->getUserCards($user);
    }

}
