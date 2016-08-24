<?php
/**
 * @package    BiBundle\Security\Acl\Role
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */


namespace BiBundle\Security\Acl\Role;

/**
 * Role factory
 */
class RoleFactory
{
    /**
     * Get role
     *
     * @param $role
     * @return RoleInterface
     */
    public static function getRole($role)
    {
        $role = strtolower($role);
        // all roles should have role_ prefix
        if (false !== ($position = strpos($role, 'role_'))) {
            $role = substr($role, $position + 5);
        }

        $parts = explode("_", $role);
        $parts = array_map(function($item){
            return ucfirst($item);
        }, $parts);
        // if we have subfolder, then add namespace prefix
        if (count($parts) > 1) {
            $role = $parts[0] . "\\";
        } else {
            $role = '';
        }
        $role = $role . join('', $parts) . 'Role';

        $class = __NAMESPACE__ . "\\" . $role;

        if (!class_exists($class)) {
            throw new \DomainException(sprintf("Role class %s not found", $class));
        }
        return new $class;
    }
}