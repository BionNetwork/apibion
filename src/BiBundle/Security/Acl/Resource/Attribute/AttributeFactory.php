<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute;
use BiBundle\Security\Acl\Resource\AbstractResource;
use BiBundle\Security\Acl\Resource\ResourceFactory;

/**
 * Creation of attributes based on some conditions
 * Factory also holds map for all attributes
 */
class AttributeFactory
{
    /**
     * @param string $resourceId resource identifier
     * @param array $data attribute data
     * @return AbstractAttribute
     */
    public static function factory($resourceId, array $data)
    {
        $map = self::getMap();
        if (!isset($map[$resourceId])) {
            throw new \DomainException(sprintf("Can't get resource attribute, resource %s does not exist", $resourceId));
        }
        $attribute = $map[$resourceId]->getAttribute($data['name']);

        if (false === $attribute) {
            throw new \DomainException(sprintf("Attribute %s does not exist in resource %s", $data['name'], $resourceId));
        }

        $attribute->fromArray($data);
        return $attribute;
    }

    /**
     * @return AbstractResource[]
     */
    protected static function getMap()
    {
        $resources = [];
        foreach (ResourceFactory::getMap() as $resourceKey => $resourceClass) {
            /** @var AbstractResource $resource */
            $resource = new $resourceClass;
            $resources[$resource->getResourceId()] = $resource;
        }
        return $resources;
    }
}