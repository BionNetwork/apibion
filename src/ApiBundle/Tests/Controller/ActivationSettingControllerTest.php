<?php


namespace ApiBundle\Tests\Controller;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Entity\User;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\Exception\ActivationSettingException;
use BiBundle\Service\TestEntityFactory;
use Ramsey\Uuid\Uuid;

class ActivationSettingControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    /** @var  TestEntityFactory */
    private $factory;

    /** @var  ActivationSettingService */
    private $service;

    /** @var  User */
    private $user;

    public static function tearDownAfterClass()
    {
        self::bootKernel();
        $factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $factory->purgeTestEntities(
            [
                ActivationSetting::class,
                Activation::class,
            ]
        );
    }

    public function setUp()
    {
        parent::setUp();
        $this->factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $this->service = self::$kernel->getContainer()->get('bi.activation_setting.service');
        $this->user = $this->getUser();
    }

    public function testGetSettings()
    {
        $activation = $this->factory->createActivation($this->user);
        $as1 = $this->service->create($activation, 'key1', 'value1');
        $as2 = $this->service->create($activation, 'key2', 'value2');
        $as3 = $this->service->create($activation, 'key3', 'value3');
        $this->service->delete($activation, 'key3');
        $this->client->request('GET', "/api/v1/activation/{$activation->getId()}/settings");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(2, $data['data']);
        $this->assertArraySubset([$as1->getKey() => $as1->getValue()], $data['data']);
        $this->assertArraySubset([$as2->getKey() => $as2->getValue()], $data['data']);
        $this->assertArrayNotHasKey($as3->getKey(), $data['data']);

    }

    public function testCreateSetting()
    {
        $activation = $this->factory->createActivation($this->user);
        $value = Uuid::uuid4()->toString();
        $key = 'key1';
        $this->client->request(
            'POST',
            "/api/v1/activation/{$activation->getId()}/setting/$key",
            ['value' => $value]
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $setting = $this->service->get($activation, $key);
        $this->assertSame($key, $setting->getKey());
        $this->assertSame($value, $setting->getValue());

        return [$activation, $setting];
    }

    /**
     * @depends testCreateSetting
     */
    public function testUpdateSetting(array $params)
    {
        /** @var ActivationSetting $activationSetting */
        /** @var Activation $activation */
        list($activation, $activationSetting) = $params;
        $newValue = Uuid::uuid4()->toString();
        $this->client->request(
            'PUT',
            "/api/v1/activation/{$activation->getId()}/setting/{$activationSetting->getKey()}",
            ['value' => $newValue]
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $updatedSetting = $this->service->get($activation, $activationSetting->getKey());
        $this->assertSame($newValue, $updatedSetting->getValue());

        return [$activation, $activationSetting, $updatedSetting];
    }

    /**
     * @depends testUpdateSetting
     */
    public function testGetSetting(array $params)
    {
        /** @var ActivationSetting $previousActivationSetting */
        /** @var ActivationSetting $currentActivationSetting */
        /** @var Activation $activation */
        list($activation, $previousActivationSetting, $currentActivationSetting) = $params;
        $this->client->request(
            'GET',
            "/api/v1/activation/{$activation->getId()}/setting/{$previousActivationSetting->getKey()}"
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey($previousActivationSetting->getKey(), $data['data']);
        $this->assertSame($currentActivationSetting->getValue(), $data['data'][$currentActivationSetting->getKey()]);

        return [$activation, $previousActivationSetting, $currentActivationSetting];
    }

    /**
     * @depends testGetSetting
     */
    public function testUndoSetting(array $params)
    {
        /** @var ActivationSetting $previousActivationSetting */
        /** @var ActivationSetting $currentActivationSetting */
        /** @var Activation $activation */
        list($activation, $previousActivationSetting, $currentActivationSetting) = $params;
        $this->client->request(
            'POST',
            "/api/v1/activation/{$activation->getId()}/setting/{$previousActivationSetting->getKey()}/undo"
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $setting = $this->service->get($activation, $previousActivationSetting->getKey());
        $this->assertSame($previousActivationSetting->getValue(), $setting->getValue());
    }

    public function testDeleteSetting()
    {
        $activation = $this->factory->createActivation($this->user);
        $activationSetting = $this->service->create($activation, 'key1', 'value1');
        $this->client->request(
            'DELETE',
            "/api/v1/activation/{$activation->getId()}/setting/{$activationSetting->getKey()}"
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->expectException(ActivationSettingException::class);
        $this->service->get($activation, $activationSetting->getKey());
    }

    public function testUnauthorizedGet()
    {
        $activation = $this->factory->createActivation($this->getUser('administrator'));
        $this->client->request('GET', "/api/v1/activation/{$activation->getId()}/settings");
        $this->assert403($this->client->getResponse());
    }

    public function testInvalidUndo()
    {
        $activation = $this->factory->createActivation($this->user);
        $activationSetting = $this->service->create($activation, 'key1', 'value1');
        $this->client->request(
            'POST',
            "/api/v1/activation/{$activation->getId()}/setting/{$activationSetting->getKey()}/undo"
        );

        $this->assert400($this->client->getResponse());
    }

    public function testInvalidRedo()
    {
        $activation = $this->factory->createActivation($this->user);
        $activationSetting = $this->service->create($activation, 'key1', 'value1');
        $this->service->update($activation, $activationSetting->getKey(), 'value2');
        $this->service->undo($activation, $activationSetting->getKey());
        $this->service->update($activation, $activationSetting->getKey(), 'value3');
        $this->client->request(
            'POST',
            "/api/v1/activation/{$activation->getId()}/setting/{$activationSetting->getKey()}/redo"
        );

        $this->assert400($this->client->getResponse());
    }
}
