<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute\Project
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Project;

use BiBundle\Entity\Project;
use BiBundle\Entity\User;
use BiBundle\Security\Acl\Resource\Attribute\AttributeInterface as BaseInterface;

/**
 * Interface AttributeInterface
 *
 * Base interface for issue attributes
 */
interface AttributeInterface extends BaseInterface
{
    /**
     * Vote on issue attribute
     *
     * @param Project $project
     * @param User $user
     * @return mixed
     */
    public function vote(Project $project, User $user);
}