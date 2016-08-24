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

class AcceptAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Отклонение/подтверждение завершения проекта';

    public function getName()
    {
        return 'accept';
    }

    public function vote(Project $project, User $user)
    {
        return false;
    }
}