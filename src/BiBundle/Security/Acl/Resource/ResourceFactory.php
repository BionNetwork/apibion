<?php
/**
 * @package    BiBundle\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */


namespace BiBundle\Security\Acl\Resource;

/**
 * Factory for all resource
 */
class ResourceFactory
{
    /**
     * Map of all resources
     * All available resources should be registered here
     *
     * @return array
     */
    public static function getMap()
    {
        $map = [
            'project' => ProjectResource::class,
            'issue' => IssueResource::class
        ];
        return $map;
    }
    /**
     * @param $resource
     * @param array $attributes
     * @return AbstractResource
     */
    public static function factory($resource, array $attributes)
    {
        $map = self::getMap();

        if (isset($map[$resource])) {
            $entity = new $map[$resource]($attributes);
        } else {
            throw new \DomainException("Unknown resource given: " . $resource);
        }

        return $entity;
    }
}