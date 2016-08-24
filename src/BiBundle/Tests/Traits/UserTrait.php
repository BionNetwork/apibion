<?php

/**
 * @package    BiBundle\Tests\Traits
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\UserRole;
use BiBundle\Entity\UserStatus;

trait UserTrait
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * Cache for statuses
     *
     * @var array
     */
    private $statuses = array();
    /**
     * Cache for roles
     *
     * @var array
     */
    private $roles = array();
    /**
     * @param string $code
     * @return null|object|UserStatus
     */
    protected function getUserStatus($code = UserStatus::STATUS_ACTIVE)
    {
        if (isset($this->statuses[$code])) {
            return $this->statuses[$code];
        }
        $container = $this->container;
        $repository = $container->get('repository.user_repository');
        $status = $repository->getStatus($code);
        $this->statuses[$code] = $status;
        return $this->statuses[$code];
    }

    /**
     * @param string $code
     * @return null|object|UserRole
     */
    protected function getUserRole($code = UserRole::ROLE_USER)
    {
        if (isset($this->roles[$code])) {
            return $this->roles[$code];
        }
        $container = $this->container;
        $repository = $container->get('repository.user_repository');
        $role = $repository->getRole($code);
        $this->roles[$code] = $role;
        return $this->roles[$code];
    }
}