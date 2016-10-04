<?php


namespace ApiBundle\Tests\Controller;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\TestEntityFactory;
use Ramsey\Uuid\Uuid;

class ActivationSettingControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    /** @var  TestEntityFactory */
    private $factory;

    /** @var  ActivationSettingService */
    private $service;

    public function setUp()
    {
        parent::setUp();
        $this->factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $this->service = self::$kernel->getContainer()->get('bi.activation_setting.service');
    }

    public function testGetSettings()
    {
        $activation = $this->factory->createActivation();
        $as1 = $this->service->create($activation, 'key1', 'value1');
        $as2 = $this->service->create($activation, 'key2', 'value2');
        $this->client->request('GET', "/api/v1/activation/{$activation->getId()}/settings");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(2, $data);
        // TODO: check keys and values
        $this->markTestIncomplete();
    }

    public function testCreateSetting()
    {
        $activation = $this->factory->createActivation();
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
        $setting = $this->service->get($activation, $activationSetting->getKey());
        $this->assertSame($newValue, $setting->getValue());
    }
}
