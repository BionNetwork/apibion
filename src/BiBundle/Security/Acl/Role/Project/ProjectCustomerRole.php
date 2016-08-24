<?php
/**
 * @package    BiBundle\Security\Acl\Role\Project
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Role\Project;

use BiBundle\Entity\MemberRole;
use BiBundle\Security\Acl\Role\RoleInterface;

/**
 * Customer of project role
 */
class ProjectCustomerRole implements RoleInterface
{
    public function getRole()
    {
        return MemberRole::ROLE_CUSTOMER;
    }
}