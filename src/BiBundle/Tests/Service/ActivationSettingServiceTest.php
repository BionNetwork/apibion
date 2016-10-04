<?php

namespace BiBundle\Tests\Service;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\TestEntityFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ActivationSettingServiceTest extends KernelTestCase
{
    /** @var  TestEntityFactory */
    private $factory;

    /** @var  ActivationSettingService */
    private $service;

    /** @var  EntityManager */
    private $entityManager;

    protected function setUp()
    {
        self::bootKernel();
        $this->factory = self::$kernel->getContainer()->get('bi.test_entity.factory');
        $this->service = self::$kernel->getContainer()->get('bi.activation_setting.service');
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testCreate()
    {
        $key = 'key1';
        $value = 'value1';
        $activation = $this->factory->createActivation();
        $this->service->create($activation, $key, $value);

        $activationSetting = $this->entityManager->getRepository(ActivationSetting::class)->findOneBy(
            [
                'activation' => $activation,
                'key' => $key
            ]
        );
        $this->assertNotNull($activationSetting);

        return [$activation, ['key' => $key, 'value' => $value]];
    }

    /**
     * @depends testCreate
     */
    public function testGet(array $data)
    {
        list($activation, $expected) = $data;
        $setting = $this->service->get($activation, $expected['key']);
        $this->assertNotNull($setting);
        $this->assertSame($expected['value'], $setting->getValue());
    }

    public function testGetAll()
    {
        $activation = $this->entityManager->getRepository(Activation::class)->find(7);

        $setting = $this->service->getAll($activation);
        $this->assertNotNull($setting);
    }
}
