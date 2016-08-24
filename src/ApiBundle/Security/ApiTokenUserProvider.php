<?php
/**
 * @package    ApiBundle\Security
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Security;

use ApiBundle\Service\ApiKeyProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use BiBundle\Entity\User;
use BiBundle\Repository\UserRepository;

class ApiTokenUserProvider implements UserProviderInterface
{
    const API_ROLE = 'ROLE_API';

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ApiKeyProvider
     */
    private $provider;
    /**
     * @var User
     */
    private $user;

    public function __construct(UserRepository $repository, ApiKeyProvider $provider)
    {
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * Get username for api token
     *
     * @param $token
     * @return null|string
     */
    public function getUsernameForApiToken($token)
    {
        $user = $this->getProvider()->getUserByToken($token);
        if (null === $user) {
            return null;
        }
        /** @var \BiBundle\Entity\User $user */
        $user = $this->repository->findOneBy(['id' => $user->getId()]);
        if (null === $user) {
            return null;
        }
        $this->setUser($user);

        return $user->getUsername();
    }

    /**
     * @param string $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        if (null === $this->getUser()) {
            $user = new User();
            $user->setLogin($username);
        } else {
            $user = $this->getUser();
        }
        // the roles for the user - you may choose to determine
        // these dynamically somehow based on the user
        $user->addRole(self::API_ROLE);
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User;';
    }

    public function refreshUser(UserInterface $user)
    {
        // $user is the User that you set in the token inside authenticateToken()
        // after it has been deserialized from the session

        // you might use $user to query the database for a fresh user
        // $id = $user->getId();

        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @return ApiKeyProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}