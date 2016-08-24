<?php
/**
 * @package    BiBundle\Tests\Security\Acl\Resource
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */


namespace BiBundle\Tests\Security\Acl\Resource;


use BiBundle\Security\Acl\Resource\AbstractResource;
use BiBundle\Security\Acl\Resource\Attribute\AbstractAttribute;
use BiBundle\Security\Acl\Resource\ResourceFactory;
use BiBundle\Security\Acl\Resource\IssueResource;
use BiBundle\Security\Acl\Resource\ProjectResource;

class ResourceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return [
            [
                [
                    'name' => 'project',
                    'class' => ProjectResource::class
                ]
            ],
            [
                [
                    'name' => 'issue',
                    'class' => IssueResource::class
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param array $data
     */
    public function testFactoryMethod(array $data)
    {
        $resource = ResourceFactory::factory($data['name'], []);
        $this->assertInstanceOf($data['class'], $resource);
        $this->assertInstanceOf(AbstractResource::class, $resource);
    }

    /**
     * @expectedException \DomainException
     */
    public function testFactoryMethodWithInvalidResource()
    {
        ResourceFactory::factory('foo', []);
    }

    public function testFactoryMethodWithEmptyAttributes()
    {
        $resource = ResourceFactory::factory('project', []);
        $this->assertTrue($resource->getAttributes()->count() > 0, "resource can't have empty attributes");
    }

    public function testFactoryMethodReturnsResourceWithAttributesFilled()
    {
        $attributes = [
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
			],
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
        ];

        $resource = ResourceFactory::factory('project', $attributes);
        $this->assertTrue($resource->hasAttribute('accept'), "accept attribute must be present");
        $this->assertTrue($resource->hasAttribute('accept_role'), "accept_role attribute must be present");
        $this->assertFalse($resource->hasAttribute('view'), "view attribute should be absent");

        $accept = $resource->getAttribute('accept');
        $this->assertNotFalse($accept);
        $this->assertInstanceOf(AbstractAttribute::class, $accept);

        $this->assertAttribute($attributes[0], $accept);

        $acceptRole = $resource->getAttribute('accept_role');
        $this->assertNotFalse($acceptRole);
        $this->assertInstanceOf(AbstractAttribute::class, $acceptRole);

        $this->assertAttribute($attributes[1], $acceptRole);
    }

    /**
     * Supply function to test attribute methods
     *
     * @param $data
     * @param AbstractAttribute $attribute
     */
    protected function assertAttribute($data, AbstractAttribute $attribute)
    {
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