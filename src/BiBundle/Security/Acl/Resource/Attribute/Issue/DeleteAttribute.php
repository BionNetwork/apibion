<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute\Issue
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Issue;

use BiBundle\Entity\Issue;
use BiBundle\Entity\User;
use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;

class DeleteAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Удаление задачи';

    public function getName()
    {
        return 'delete';
    }

    public function vote(Issue $issue, User $user)
    {
        return false;
    }
}