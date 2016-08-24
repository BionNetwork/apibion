<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute\Grant
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Grant;
use BiBundle\Security\Acl\Role\RoleInterface;

/**
 * Interface GrantInterface
 * Grant interface for role
 */
interface GrantInterface
{
    /**
     * User's role
     *
     * @return RoleInterface
     */
    public function getRole();

    /**
     * Grant for role
     *
     * @return mixed
     */
    public function getGrant();

    /**
     * @return array
     */
    public function toArray();
}