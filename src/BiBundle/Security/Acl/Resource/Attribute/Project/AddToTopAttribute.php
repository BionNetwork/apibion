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

class AddToTopAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Перемещение в избранные';

    public function getName()
    {
        return 'add_to_top';
    }

    public function vote(Project $project, User $user)
    {
        return false;
    }
}