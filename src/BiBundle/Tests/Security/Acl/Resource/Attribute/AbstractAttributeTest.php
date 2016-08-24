<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Resource\Attribute
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Security\Acl\Resource\Attribute;

use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;
use BiBundle\Security\Acl\Resource\Attribute\Grant\Grant;
use BiBundle\Security\Acl\Resource\Attribute\Grant\GrantInterface;
use BiBundle\Security\Acl\Role\Project\ProjectCustomerRole;
use BiBundle\Security\Acl\Role\RoleInterface;

class AbstractAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractAttribute
     */
    protected $mock;

    /**
     * @return AbstractAttribute
     */
    public function getAttribute()
    {
        return $this->mock;
    }

    protected function setUp()
    {
        $this->mock = $this->getMockForAbstractClass(AbstractAttribute::class);
    }

    public function attributesData()
    {
        return [
            [
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
            ],
            [
                [
                    "attribute" => "accept_role",
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

    public function testToArrayMethod()
    {
        foreach (array('attribute', 'text', 'description', 'type', 'default', 'grants') as $key) {
            $this->assertArrayHasKey($key, $this->getAttribute()->toArray());
        }
    }

    /**
     * @dataProvider attributesData
     * @param array $data
     */
    public function testToArrayWithData(array $data)
    {
        $this->getAttribute()->fromArray($data);
        $item = $this->getAttribute()->toArray();
        foreach (array('text', 'description', 'type', 'default') as $key) {
            $this->assertEquals($data[$key], $item[$key]);
        }
    }

    /**
     * @dataProvider attributesData
     * @param array $data
     */
    public function testFromArrayMethod(array $data)
    {
        $this->getAttribute()->fromArray($data);
        $attribute = $this->getAttribute();

        $this->assertEquals($data['text'], $attribute->getText());
        $this->assertEquals($data['description'], $attribute->getDescription());
        $this->assertEquals($data['default'], $attribute->getDefault());
        $this->assertEquals($data['type'], $attribute->getType());

        $this->assertEquals(count($data['grants']), $attribute->getGrants()->count());
        foreach ($data['grants'] as $grantItem) {
            $grant = $attribute->getGrant($grantItem['role']);
            $this->assertNotFalse($grant, "grant is not found");
            $this->assertEquals(strtoupper($grantItem['role']), $grant->getRole()->getRole());
            $this->assertEquals($grantItem['grant'], $grant->getGrant());
        }
    }

    /**
     * @dataProvider attributesData
     * @param array $data
     */
    public function testGetGrantsMethod(array $data)
    {
        $this->getAttribute()->fromArray($data);
        $grants = $this->getAttribute()->getGrants();
        /** @var GrantInterface $grant */
        foreach ($grants as $grant) {
            $this->assertInstanceOf(GrantInterface::class, $grant);
            $this->assertNotNull($grant->getGrant());
            $this->assertNotNull($grant->getRole());
            $this->assertInstanceOf(RoleInterface::class, $grant->getRole());
        }
    }

    public function testGetGrantMethod()
    {
        $data = [
            "grants" => [
                [
                    "role" => "role_project_customer",
                    "grant" => true
                ]
            ]
        ];
        $this->getAttribute()->fromArray($data);
        $this->assertCount(count($data['grants']), $this->getAttribute()->getGrants());
        $grant = $this->getAttribute()->getGrant('role_project_customer');
        $this->assertInstanceOf(GrantInterface::class, $grant);
        $this->assertEquals($data['grants'][0]['grant'], $grant->getGrant());
        $this->assertInstanceOf(RoleInterface::class, $grant->getRole());
        $this->assertEquals(strtoupper($data['grants'][0]['role']), $grant->getRole()->getRole());
    }

    public function testAddGrantMethod()
    {
        $role = new ProjectCustomerRole();
        $grant = new Grant($role, true);
        $this->getAttribute()->addGrant($grant);
        $this->assertCount(1, $this->getAttribute()->getGrants());
        $this->assertNotFalse($this->getAttribute()->getGrant($role->getRole()));
        $this->assertInstanceOf(RoleInterface::class, $this->getAttribute()->getGrant($role->getRole())->getRole());
        $this->assertEquals($role->getRole(), $this->getAttribute()->getGrant($role->getRole())->getRole()->getRole());
        return $this->getAttribute();
    }

    /**
     * @depends testAddGrantMethod
     * @param AbstractAttribute $attribute
     */
    public function testRemoveGrantMethod($attribute)
    {
        $role = new ProjectCustomerRole();
        $this->assertCount(1, $attribute->getGrants());
        $grant = $attribute->getGrant($role->getRole());
        $this->assertNotFalse($grant);
        $attribute->removeGrant($grant);
        $this->assertCount(0, $attribute->getGrants());
    }
}