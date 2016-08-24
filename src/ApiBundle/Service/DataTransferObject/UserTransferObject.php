<?php
/**
 * @package    ApiBundle\Service\DataTransferObject
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\User;
use BiBundle\Repository\UserRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class UserTransferObject
{
    /**
     * @var array
     */
    private $currentRoles;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var HostBasedUrl
     */
    private $url;

    public function __construct(UserRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->currentRoles = $userAwareService->getUser()->getRoles();
        $this->repository = $repository;
        $this->url = $url;
    }

    /**
     * Get user's data normalized
     *
     * @param User $user
     * @return array
     */
    public function getObjectData(User $user)
    {
        $data = [
            'id' => $user->getId(),
            'avatar' => $this->url->getUrl($user->getAvatar()),
            'avatar_small' => $this->url->getUrl($user->getAvatarSmall()),
            'birth_date' => $user->getBirthDate(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstname(),
            'last_name' => $user->getLastname(),
            'middle_name' => $user->getMiddlename(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'phones' => [],
            'position' => $user->getPosition(),
        ];
        $userVO = Object\UserValueObject::fromArray($data);
        // make role based data transformations
        return $userVO;
    }

    /**
     * @return UserRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}