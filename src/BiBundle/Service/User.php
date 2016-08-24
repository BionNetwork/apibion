<?php

namespace BiBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use BiBundle\Entity\User as EntityUser;

class User extends UserAwareService
{
    /**
     * @var UserPasswordEncoder
     */
    protected $passwordEncoder;
    /**
     * @param EntityUser $user
     */
    public function save(EntityUser $user)
    {
        $em = $this->getEntityManager();
        // Удаление контактов пользователя
        $contactRepository = $em->getRepository('BiBundle:UserContact');
        $contacts = $contactRepository->findBy(['user' => $user]);
        $currentContactCollection = $user->getContacts();
        foreach ($contacts as $contact) {
            if (!$currentContactCollection->contains($contact)) {
                $em->remove($contact);
            }
        }
        // Дублируем основной номер телефона в таблицу контактов
        $this->provideBasePhoneNumber($user);

        $em->persist($user);
        $em->flush();
    }

    /**
     * Дублирует основной номер телефона в таблицу контактов
     *
     * @param \BiBundle\Entity\User $user
     */
    protected function provideBasePhoneNumber(EntityUser $user)
    {
        $defaultContact = null;
        foreach ($user->getContacts() as $userContact) {
            if (true === $userContact->getIsDefault()) {
                $defaultContact = $userContact;
                break;
            }
        }
        if ($defaultContact) {
            if ($user->getPhone()) {
                // Основной телефон уже есть. Обновим его.
                $defaultContact->setValue($user->getPhone());
            } else {
                // Основной телефон есть, но он не нужен. Удалим его.
                $em = $this->getEntityManager();
                $contactRepo = $em->getRepository('BiBundle:UserContact');
                $needDeleteContact = $contactRepo->find($defaultContact->getId());
                if ($needDeleteContact) {
                    $em->remove($needDeleteContact);
                    $user->removeContact($defaultContact);
                }
            }
        } else {
            if ($user->getPhone()) {
                // Основного телефона нет, создадим его.
                $defaultContact = new \BiBundle\Entity\UserContact();
                $defaultContact->setType(\BiBundle\Entity\UserContact::TYPE_PHONE);
                $defaultContact->setIsDefault(true);
                $defaultContact->setValue($user->getPhone());
                $defaultContact->setUser($user);
                $user->addContact($defaultContact);
            }
        }
    }

    /**
     * Подготавливает к сохранению пользователя
     * @param EntityUser $user
     */
    public function prepareUserToSave(EntityUser $user)
    {
        // Уберем плюсы из номера телефона
        $phone = $user->getPhone();
        $adoptedPhone = preg_replace('/^\+/', '', $phone);
        $user->setPhone($adoptedPhone);
    }

    /**
     * Check user password
     *
     * @param EntityUser $user
     * @param $password
     * @return bool
     */
    public function isPasswordValid(\BiBundle\Entity\User $user, $password)
    {
        if (!$this->getPasswordEncoder()->isPasswordValid($user, $password)) {
            return false;
        }
        return true;
    }

    /**
     * @return UserPasswordEncoder
     */
    public function getPasswordEncoder()
    {
        return $this->passwordEncoder;
    }

    /**
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function setPasswordEncoder(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Поиск пользователя
     *
     * @param $query string
     *
     * @return array
     */
    public function search($query = null)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('BiBundle:User');
        return $repo->search($query);
    }
}
