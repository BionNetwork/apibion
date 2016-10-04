<?php

namespace BiBundle\Tests\Service;


use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\TestEntityFactory;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
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
        list($key, $value) = $this->getStrings(3);
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

        return [$activation, $expected];
    }

    public function testUpdate()
    {
        $activation = $this->factory->createActivation();
        list($key, $value, $newValue) = $this->getStrings(3);
        $this->service->create($activation, $key, $value);
        $this->service->update($activation, $key, $newValue);

        $setting = $this->service->get($activation, $key);
        $this->assertSame($newValue, $setting->getValue());

        return [$activation, ['key' => $key, 'value' => $value, 'currentValue' => $setting->getValue()]];
    }

    /**
     * @depends testUpdate
     */
    public function testUndo(array $data)
    {
        list($activation, $expectedKeyValue) = $data;
        $setting = $this->service->undo($activation, $expectedKeyValue['key']);

        $this->assertSame($expectedKeyValue['value'], $setting->getValue());

        return [$activation, ['key' => $expectedKeyValue['key'], 'value' => $expectedKeyValue['currentValue']]];
    }

    /**
     * @depends testUndo
     */
    public function testRedo(array $data)
    {
        list($activation, $expectedKeyValue) = $data;
        $setting = $this->service->redo($activation, $expectedKeyValue['key']);

        $this->assertSame($expectedKeyValue['value'], $setting->getValue());
    }

    private function getStrings($count)
    {
        return array_map(function () {
            return Uuid::uuid4()->toString();
        }, range(1, $count));
    }

    public function testGetAll()
    {
        $activation = $this->entityManager->getRepository(Activation::class)->find(7);

        $setting = $this->service->getAll($activation);
        $this->assertNotNull($setting);
    }
}
