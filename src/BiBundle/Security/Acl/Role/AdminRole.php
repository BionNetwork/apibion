<?php
/**
 * @package    BiBundle\Security\Acl\Role
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Role;


class AdminRole implements RoleInterface
{
    public function getRole()
    {
        return 'ROLE_ADMIN';
    }
}