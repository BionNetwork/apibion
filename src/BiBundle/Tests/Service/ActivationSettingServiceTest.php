<?php

namespace BiBundle\Tests\Service;


use BiBundle\Entity\ActivationSetting;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\Exception\ActivationSettingException;
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
        list($key, $value) = $this->getStrings(2);
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

    public function testGetAll()
    {
        $activation = $this->factory->createActivation();
        $as1 = $this->service->create($activation, 'key1', 'value1');
        $as2 = $this->service->create($activation, 'key2', 'value2');
        $as3 = $this->service->create($activation, 'key3', 'value3');
        $this->service->delete($activation, 'key3');

        $settings = $this->service->getAll($activation);

        $this->assertContains($as1, $settings);
        $this->assertContains($as2, $settings);
        $this->assertNotContains($as3, $settings);
    }

    public function testDelete()
    {
        $activation = $this->factory->createActivation();
        $as1 = $this->service->create($activation, 'key1', 'value1');
        $this->service->delete($activation, 'key1');

        $this->expectException(ActivationSettingException::class);
        $activationSetting = $this->service->get($activation, 'key1');
    }

    public function testDeleteNotExisting()
    {
        $activation = $this->factory->createActivation();
        $this->expectException(ActivationSettingException::class);
        $this->service->delete($activation, 'key1');
    }

    public function testGetNotExisting()
    {
        $activation = $this->factory->createActivation();
        $this->expectException(ActivationSettingException::class);
        $this->service->get($activation, 'key1');
    }

    private function getStrings($count)
    {
        return array_map(function () {
            return Uuid::uuid4()->toString();
        }, range(1, $count));
    }
}
