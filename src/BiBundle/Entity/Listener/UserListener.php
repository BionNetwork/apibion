<?php
/**
 * @package    BiBundle\Entity\Listener
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use BiBundle\Entity\Exception\ValidatorException;
use BiBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\Traits\ValidatorTrait;
use BiBundle\Service\Exception\UploadFileException;

class UserListener
{
    use ValidatorTrait;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param User $user
     * @return array|null
     */
    protected function getValidationGroups(User $user)
    {
        $groups = null;
        if ($user->getAvatar() && $user->getAvatar() instanceof File) {
            $groups = ['Default', 'upload'];
        }
        return $groups;
    }
    /**
     * @param User $user
     * @param LifecycleEventArgs $event
     * @throws ValidatorException
     */
    public function preUpdate(User $user, LifecycleEventArgs $event)
    {
        $userService = $this->getUserService();
        $userService->prepareUserToSave($user);

        $groups = $this->getValidationGroups($user);
        $this->validate($user, null, $groups);

        $uow = $event->getEntityManager()->getUnitOfWork();
        $entityChangeSet = $uow->getEntityChangeSet($user);

        if (trim($user->getPassword()) == '') {
            // recover old value
            $user->setPassword($entityChangeSet['password'][0]);
            $user->setPasswdChangedOn(new \DateTime());
        } else {
            if (!empty($entityChangeSet['password'])) {
                $encoder = $this->getContainer()->get('security.password_encoder');
                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $user->setPasswdChangedOn(new \DateTime());
            }
        }
        $this->convertFilePathToRelative($user);
    }

    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $groups = $this->getValidationGroups($user);
        $this->validate($user, null, $groups);

        $encoder = $this->getContainer()->get('security.password_encoder');
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        $this->convertFilePathToRelative($user);
    }

    /**
     * @return \BiBundle\Service\User
     */
    protected function getUserService()
    {
        return $this->getContainer()->get('user.service');
    }

    /**
     * Формируем пути к файлам фотографий пользователя
     *
     * @param User $user
     * @throws UploadFileException
     */
    protected function convertFilePathToRelative(User $user)
    {
        $rootDirectory = realpath($this->getContainer()->getParameter('upload_dir'));
        if ($user->getAvatar() instanceof File && $avatar = $user->getAvatar()) {
            $avatar = str_replace($rootDirectory, '', $avatar->getRealPath());
            $user->setAvatar($avatar);
        }
        if ($user->getAvatarSmall() instanceof File && $avatarSmall = $user->getAvatarSmall()) {
            $avatarSmall = str_replace($rootDirectory, '', $avatarSmall->getRealPath());
            $user->setAvatarSmall($avatarSmall);
        }
    }
}
