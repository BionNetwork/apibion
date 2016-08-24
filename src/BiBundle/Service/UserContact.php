<?php

namespace BiBundle\Service;

use Doctrine\ORM\EntityManager;

class UserContact
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Подготавливает к сохранению контакт пользователя
     * @param \BiBundle\Entity\UserContact $userContact
     */
    public function prepareContactToSave($userContact)
    {
        // Уберем плюсы из номера телефона
        if ($userContact->getType() == \BiBundle\Entity\UserContact::TYPE_PHONE) {
            $phone = $userContact->getValue();
            $adoptedPhone = preg_replace('/^\+/', '', $phone);
            $userContact->setValue($adoptedPhone);
        }
    }
}
