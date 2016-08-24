<?php
/**
 * @package    BiBundle\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource;

use BiBundle\Security\Acl\Resource\Attribute\Issue\CreateAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Issue\DeleteAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Issue\EditAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Issue\SetAssigneeAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Issue\ViewAttribute;

/**
 * Issue resource with set of attributes
 */
class IssueResource extends AbstractResource
{
    protected function init()
    {
        $this->addAttribute(new CreateAttribute());
        $this->addAttribute(new DeleteAttribute());
        $this->addAttribute(new EditAttribute());
        $this->addAttribute(new SetAssigneeAttribute());
        $this->addAttribute(new ViewAttribute());
    }

    public function getResourceId()
    {
        return 'issue';
    }
}