<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute;
/**
 * Interface AttributeInterface
 * Basic methods for every attribute in resource
 */
interface AttributeInterface
{
    /**
     * Returns array of attribute properties
     *
     * @return mixed
     */
    public function toArray();

    /**
     * Gets attribute's name
     *
     * @return mixed
     */
    public function getName();
}