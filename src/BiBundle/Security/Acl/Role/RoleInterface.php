<?php

/**
 * @package    BiBundle\Security\Acl\Role
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Role;
/**
 * Interface RoleInterface
 */
interface RoleInterface
{
    /**
     * Get role identifier
     *
     * @return string
     */
    public function getRole();
}