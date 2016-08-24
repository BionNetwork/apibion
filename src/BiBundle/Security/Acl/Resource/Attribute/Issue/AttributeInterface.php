<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute\Issue
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Issue;

use BiBundle\Entity\Issue;
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
     * @param Issue $issue
     * @param User $user
     * @return mixed
     */
    public function vote(Issue $issue, User $user);
}