<?php

namespace BiBundle\Service;

use BiBundle\Service\Exception\UserCard\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Card;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CardService extends UserAwareService
{


    /**
     * Возвращает проект по фильтру
     *
     * @param \BiBundle\Entity\Filter\Card $filter
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:Card')->findByFilter($filter);

        // Развернем в структуру
        $resultArray = [];
        foreach ($items as $row) {
            $resultArray[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'type' => $row['type'],

            ];
        }
        return $resultArray;
    }


    /**
     * Покупка карточки
     * @param \BiBundle\Entity\Card $card
     * @param \BiBundle\Entity\User $user
     */
    public function purchase($card, $user)
    {
        $em = $this->getEntityManager();
        $userCardRepository = $em->getRepository('BiBundle:UserCard');

        $userCard = $userCardRepository->findBy(['user_id' => $user->getID(), 'card_id' => $card->getId()]);
        
        if(null == $userCard) {
            $userCard = new \BiBundle\Entity\UserCard();
            $userCard->setCard($card);
            $userCard->setUser($user->getId());
        } else {
            throw new AlreadyPurchasedException('Карточка уже приобретена');
        }

        $em->persist($userCard);
        $em->flush();
    }
}
