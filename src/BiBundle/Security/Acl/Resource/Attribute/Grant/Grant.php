<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Grant;

use BiBundle\Security\Acl\Role\RoleInterface;

class Grant implements GrantInterface
{
    /**
     * @var RoleInterface
     */
    protected $role;
    /**
     * @var mixed
     */
    protected $grant;

    /**
     * Set role identifier and its grant option
     * @param RoleInterface $role
     * @param $grant
     */
    public function __construct(RoleInterface $role, $grant)
    {
        $this->role = $role;
        $this->grant = $grant;
    }

    /**
     * Get role identifier
     *
     * @return RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get grant option
     *
     * @return mixed
     */
    public function getGrant()
    {
        return $this->grant;
    }

    /**
     * Array presentation of grant
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'role' => strtolower($this->getRole()->getRole()),
            'grant' => $this->getGrant()
        ];
    }
}