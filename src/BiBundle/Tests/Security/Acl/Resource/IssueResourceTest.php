<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Security\Acl\Resource;

use BiBundle\Security\Acl\Resource\Attribute\Issue\AttributeInterface;
use BiBundle\Security\Acl\Resource\IssueResource;

class IssueResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAttributes()
    {
        $resource = new IssueResource();
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $resource->getAttributes());
        $this->assertNotEmpty($resource->getAttributes());
    }

    public function attributeProvider()
    {
        return [
            [
                'view',
                'create',
                'delete',
                'edit',
                'set_assignee'
            ]
        ];
    }

    /**
     * @dataProvider attributeProvider
     * @param string $name
     */
    public function testHasAttribute($name)
    {
        $resource = new IssueResource();
        $this->assertTrue($resource->hasAttribute($name));
    }

    public function testHasAttributeWithUnknownAttribute()
    {
        $resource = new IssueResource();
        $this->assertFalse($resource->hasAttribute('foo'));
    }

    /**
     * @dataProvider attributeProvider
     * @param string $name
     */
    public function testGetAttribute($name)
    {
        $resource = new IssueResource();
        $attribute = $resource->getAttribute($name);
        $this->assertNotNull($attribute);
        $this->assertInstanceOf(AttributeInterface::class, $attribute);
        $this->assertEquals($name, $attribute->getName(), "attribute name is wrong");
    }

    public function testGetAttributeWithUnknownAttributeReturnsFalse()
    {
        $resource = new IssueResource();
        $attribute = $resource->getAttribute('foo');
        $this->assertFalse($attribute);
    }
}