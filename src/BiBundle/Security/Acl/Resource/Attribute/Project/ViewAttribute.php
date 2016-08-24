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
use BiBundle\Security\Acl\Resource\Attribute\Grant\GrantInterface;

class ViewAttribute extends AbstractAttribute implements AttributeInterface
{
    protected $text = 'Просмотр сведений о проекте';

    public function getName()
    {
        return 'view';
    }

    public function vote(Project $project, User $user)
    {
        $attributeGrant = false;

        /** @var GrantInterface $grant */
        foreach ($this->getGrants() as $grant) {
            if (in_array($grant->getRole()->getRole(), $user->getRoles())) {
                $attributeGrant = $grant;
                break;
            }
        }
        if ($attributeGrant) {
            return (bool)$attributeGrant->getGrant();
        }
        return (bool)$this->getDefault();
    }
}