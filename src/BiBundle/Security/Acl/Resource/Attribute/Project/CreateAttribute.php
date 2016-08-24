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

class CreateAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Создание проекта';

    public function getName()
    {
        return 'create';
    }

    public function vote(Project $project, User $user)
    {
        return false;
    }
}