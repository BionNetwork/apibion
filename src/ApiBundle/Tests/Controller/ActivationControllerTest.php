<?php

namespace ApiBundle\Tests\Controller;

use BiBundle\Entity\Activation;
use BiBundle\Entity\User;
use BiBundle\Service\TestEntityFactory;

class ActivationControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    /** @var  TestEntityFactory */
    private $factory;

    /** @var  User */
    private $user;

    public static function tearDownAfterClass()
    {
        self::bootKernel();
        $factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $factory->purgeTestEntities([Activation::class]);
    }

    public function setUp()
    {
        parent::setUp();
        $this->factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $this->user = $this->getUser();
    }

    public function testGetActivations()
    {
        $activation = $this->factory->createActivation($this->user);

        $this->client->request('GET', "/api/v1/activations?id={$activation->getId()}");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);

        $responseActivation = array_shift($data['data']);
        $this->assertEquals($activation->getId(), $responseActivation['id']);
        $this->assertEquals($activation->getCard()->getId(), $responseActivation['card']['id']);
    }
}
