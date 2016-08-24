<?php
/**
 * @package    BiBundle\Security\Acl\Resource\Attribute\Project
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource\Attribute\Project;

use BiBundle\Entity\Project;
use BiBundle\Entity\User;
use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;

class DeleteAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Удаление проекта';

    public function getName()
    {
        return 'delete';
    }

    public function vote(Project $project, User $user)
    {
        return false;
    }
}