<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Security\Acl\Resource\Attribute;

use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;
use BiBundle\Security\Acl\Resource\Attribute\AttributeFactory;

class AttributeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return [
            [
                [
                    "name" => "accept",
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
            ],
            [
                [
                    "name" => "accept_role",
                    "text" => "Another text",
                    "description" => "Another description",
                    "type" => "bool",
                    "default" => false,
                    "grants" => [
                        [
                            "role" => "role_project_customer",
                            "grant" => false
                        ],
                        [
                            "role" => "role_project_assignee",
                            "grant" => false
                        ],
                        [
                            "role" => "role_project_watcher",
                            "grant" => true
                        ],
                        [
                            "role" => "role_project_author",
                            "grant" => false
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     */
    public function testFactory(array $data)
    {
        $attribute = AttributeFactory::factory('project', $data);
        $this->assertAttribute($data, $attribute);
    }

    /**
     * Supply function to test attribute methods
     *
     * @param $data
     * @param AbstractAttribute $attribute
     */
    protected function assertAttribute($data, AbstractAttribute $attribute)
    {
        $this->assertEquals($data['name'], $attribute->getName());
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

    /**
     * @expectedException \DomainException
     */
    public function testFactoryReturnsExceptionWithUnknownResource()
    {
        AttributeFactory::factory('foo', []);
    }

    /**
     * @expectedException \DomainException
     */
    public function testFactoryReturnsExceptionWithUnknownAttribute()
    {
        $attribute = [
            "name" => "foo",
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
        ];
        AttributeFactory::factory('project', $attribute);
    }
}