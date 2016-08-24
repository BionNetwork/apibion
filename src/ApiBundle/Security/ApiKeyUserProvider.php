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
use Symfony\Component\Security\Core\User\User;

class ApiKeyUserProvider implements UserProviderInterface
{
    const API_ROLE = 'ROLE_API';

    /**
     * @var ApiKeyProvider
     */
    private $provider;

    public function __construct(ApiKeyProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get api key name
     *
     * @param string $key
     * @return null|string
     */
    public function getApiKey($key)
    {
        $data = $this->getProvider()->getApiKey($key);
        if (null === $data) {
            return null;
        }

        if (!empty($data['valid_until']) && $data['valid_until'] <= time()) {
            return null;
        }

        return $data['description'];
    }

    /**
     * @param string $name
     * @return User
     */
    public function loadUserByUsername($name)
    {
        return new User(
            $name,
            null,
            array(self::API_ROLE)
        );
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
}