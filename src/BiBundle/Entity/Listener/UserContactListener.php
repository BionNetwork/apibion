<?php

namespace BiBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use BiBundle\Entity\Exception\ValidatorException;
use BiBundle\Entity\UserContact;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\Traits\ValidatorTrait;

class UserContactListener
{

    use ValidatorTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param UserContact $userContact
     * @param LifecycleEventArgs $event
     * @throws ValidatorException
     */
    public function preUpdate(UserContact $userContact, LifecycleEventArgs $event)
    {
        $userContactService = $this->getContainer()->get('usercontact.service');
        $userContactService->prepareContactToSave($userContact);
        $this->validate($userContact);
    }

    public function prePersist(UserContact $userContact, LifecycleEventArgs $event)
    {
        $userContactService = $this->getContainer()->get('usercontact.service');
        $userContactService->prepareContactToSave($userContact);
        $this->validate($userContact);
    }
}
