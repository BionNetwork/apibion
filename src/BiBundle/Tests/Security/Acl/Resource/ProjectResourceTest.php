<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Security\Acl\Resource;

use BiBundle\Security\Acl\Resource\Attribute\Project\AttributeInterface;
use BiBundle\Security\Acl\Resource\ProjectResource;

class ProjectResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAttributes()
    {
        $resource = new ProjectResource();
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $resource->getAttributes());
        $this->assertNotEmpty($resource->getAttributes());
    }

    public function attributeProvider()
    {
        return [
            [
                'accept',
                'accept_role',
                'add_to_top',
                'close',
                'create',
                'delete',
                'edit',
                'sort',
                'view'
            ]
        ];
    }

    /**
     * @dataProvider attributeProvider
     * @param string $name
     */
    public function testHasAttribute($name)
    {
        $resource = new ProjectResource();
        $this->assertTrue($resource->hasAttribute($name));
    }

    public function testHasAttributeWithUnknownAttribute()
    {
        $resource = new ProjectResource();
        $this->assertFalse($resource->hasAttribute('foo'));
    }

    /**
     * @dataProvider attributeProvider
     * @param string $name
     */
    public function testGetAttribute($name)
    {
        $resource = new ProjectResource();
        $attribute = $resource->getAttribute($name);
        $this->assertNotNull($attribute);
        $this->assertInstanceOf(AttributeInterface::class, $attribute);
        $this->assertEquals($name, $attribute->getName(), "attribute name is wrong");
    }

    public function testGetAttributeWithUnknownAttributeReturnsFalse()
    {
        $resource = new ProjectResource();
        $attribute = $resource->getAttribute('foo');
        $this->assertFalse($attribute);
    }

    public function testAttributesCanBeInjected()
    {
        $data = [
            [
                "attribute" => "accept",
                "text" => "Some text here",
                "description" => "Some description",
                "type" => "bool",
                "default" => true,
                "grants" => [
                    [
                        "role" => "role_project_customer",
                        "grant" => true
                    ]
                ]
            ]
        ];
        $resource = new ProjectResource($data);
        $this->assertCount(count($data), $resource->getAttributes());
        $data = $data[0];
        $this->assertTrue($resource->hasAttribute($data['attribute']));
        $attribute = $resource->getAttribute($data['attribute']);
        $this->assertInstanceOf(AttributeInterface::class, $attribute);
        $this->assertEquals($data['attribute'], $attribute->getName());
        $this->assertEquals($data['text'], $attribute->getText());
        $this->assertEquals($data['description'], $attribute->getDescription());
        $this->assertEquals($data['default'], $attribute->getDefault());
        $this->assertEquals($data['type'], $attribute->getType());
        $this->assertEquals(count($data['grants']), $attribute->getGrants()->count());
        foreach ($data['grants'] as $grantItem) {
            $grant = $attribute->getGrant($grantItem['role']);
            $this->assertNotFalse($grant);
            $this->assertEquals(strtoupper($grantItem['role']), $grant->getRole()->getRole());
            $this->assertEquals($grantItem['grant'], $grant->getGrant());
        }
    }
}