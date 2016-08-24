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

class EditAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Редактирование задачи';

    public function getName()
    {
        return 'edit';
    }

    public function vote(Issue $issue, User $user)
    {
        return false;
    }
}