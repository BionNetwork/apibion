<?php
/**
 * @package    ApiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service;

use ApiBundle\Service\Exception\AuthenticateException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use BiBundle\Repository\UserRepository;

/**
 * Authenticate service
 */
class Authenticate
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoder $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * Returns authenticated user
     *
     * @param $login
     * @param $password
     * @return \BiBundle\Entity\User
     */
    public function authenticate($login, $password)
    {
        if (empty($login) || empty($password)) {
            throw new HttpException(400, 'Bad Request');
        }

        $user = $this->findUserByLogin($login);
        if (!$this->getEncoder()->isPasswordValid($user, $password)) {
            throw new AuthenticateException(401, "Неверные данные для аутентификации");
        }

        return $user;
    }

    /**
     * @param $login
     * @return \BiBundle\Entity\User
     */
    public function findUserByLogin($login)
    {
        $user = $this->getUserRepository()->findOneBy(['login' => $login]);
        if (null === $user) {
            throw new HttpException(404, 'Пользователь не найден');
        }
        return $user;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * @return UserPasswordEncoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Check if user with phone exists
     *
     * @param $phone
     * @return bool
     */
    public function validPhone($phone)
    {
        $user = $this->getUserRepository()->findOneBy(['phone' => $phone]);
        if (null === $user) {
            return false;
        }
        return true;
    }
}