<?php
/**
 * @package    BiBundle\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource;
/**
 * Interface ResourceInterface
 */
interface ResourceInterface
{
    /**
     * Resource identifier
     *
     * @return mixed
     */
    public function getResourceId();
}