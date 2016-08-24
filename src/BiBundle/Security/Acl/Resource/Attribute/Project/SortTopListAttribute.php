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

class SortTopListAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Перемещение по списку';

    public function getName()
    {
        return 'sort_top';
    }

    public function vote(Project $project, User $user)
    {
        return false;
    }
}