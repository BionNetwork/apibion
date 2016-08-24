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

class SetAssigneeAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Назначение руководителя к задаче';

    public function getName()
    {
        return 'set_assignee';
    }

    public function vote(Issue $issue, User $user)
    {
        return false;
    }
}