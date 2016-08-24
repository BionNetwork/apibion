<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Role
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Security\Acl\Role;

use BiBundle\Security\Acl\Role\RoleFactory;
use BiBundle\Security\Acl\Role\RoleInterface;
use BiBundle\Security\Acl\Role;

class RoleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRoleMethod()
    {
        $role = RoleFactory::getRole('role_user');
        $this->assertInstanceOf(RoleInterface::class, $role);
        $this->assertInstanceOf(\BiBundle\Security\Acl\Role\UserRole::class, $role);
    }

    public function testGetRoleWithUppercase()
    {
        $role = RoleFactory::getRole('ROLE_USER');
        $this->assertInstanceOf(RoleInterface::class, $role);
        $this->assertInstanceOf(Role\UserRole::class, $role);
    }

    public function testGetRoleFindsCorrectRole()
    {
        $role = RoleFactory::getRole('ROLE_PROJECT_AUTHOR');
        $this->assertInstanceOf(RoleInterface::class, $role);
        $this->assertInstanceOf(Role\Project\ProjectAuthorRole::class, $role);
    }

    public function testGetRoleWithAbsentRolePrefixFindsCorrectRole()
    {
        $role = RoleFactory::getRole('PROJECT_AUTHOR');
        $this->assertInstanceOf(RoleInterface::class, $role);
        $this->assertInstanceOf(Role\Project\ProjectAuthorRole::class, $role);
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetRoleWithInvalidRole()
    {
        RoleFactory::getRole('foo');
    }
}