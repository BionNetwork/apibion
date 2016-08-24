<?php

namespace BiBundle\Service;

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

}
