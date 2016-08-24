<?php
/**
 * @package    BiBundle\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Security\Acl\Resource;

use BiBundle\Security\Acl\Resource\Attribute\Project\AcceptAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\AcceptRoleAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\AddToTopAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\CloseAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\CreateAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\DeleteAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\EditAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\SortTopListAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Project\ViewAttribute;


/**
 * Project resource with set of attributes
 */
class ProjectResource extends AbstractResource
{
    protected function init()
    {
        $this->addAttribute(new AcceptAttribute());
        $this->addAttribute(new AcceptRoleAttribute());
        $this->addAttribute(new AddToTopAttribute());
        $this->addAttribute(new CloseAttribute());
        $this->addAttribute(new CreateAttribute());
        $this->addAttribute(new DeleteAttribute());
        $this->addAttribute(new EditAttribute());
        $this->addAttribute(new SortTopListAttribute());
        $this->addAttribute(new ViewAttribute());
    }

    public function getResourceId()
    {
        return 'project';
    }
}