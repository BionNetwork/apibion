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

    public function testUndo()
    {
        $activation = $this->factory->createActivation();
        $key = 'key1';
        $this->service->create($activation, $key, 'value1');
        $this->service->update($activation, $key, 'value2');
        $this->service->update($activation, $key, 'value3');
        $this->service->undo($activation, $key);

        $this->assertSame('value2', $this->service->get($activation, $key)->getValue());
        $this->service->undo($activation, $key);
        $this->assertSame('value1', $this->service->get($activation, $key)->getValue());
        $this->expectException(ActivationSettingException::class);
        $this->service->undo($activation, $key);
    }

    public function testRedo()
    {
        $activation = $this->factory->createActivation();
        $key = 'key1';
        $this->service->create($activation, $key, 'value1');
        $this->service->update($activation, $key, 'value2');
        $this->service->update($activation, $key, 'value3');
        $this->service->undo($activation, $key);
        $this->service->undo($activation, $key);
        $this->service->redo($activation, $key);

        $setting = $this->service->get($activation, $key);
        $this->assertSame('value2', $setting->getValue());

        $this->service->redo($activation, $key);

        $setting = $this->service->get($activation, $key);
        $this->assertSame('value3', $setting->getValue());

        $this->expectException(ActivationSettingException::class);
        $this->service->redo($activation, $key);
    }

    public function testRedoAfterUpdate()
    {
        $activation = $this->factory->createActivation();
        $key = 'key1';
        $this->service->create($activation, $key, 'value1');
        $this->service->update($activation, $key, 'value2');
        $this->service->undo($activation, $key);
        $this->service->update($activation, $key, 'value3');

        $this->expectException(ActivationSettingException::class);
        $this->service->redo($activation, $key);
    }

    public function testGetAll()
    {
        $activation = $this->factory->createActivation();
        $as1 = $this->service->create($activation, 'key1', 'value1');
        $as2 = $this->service->create($activation, 'key2', 'value2');
        $as21 = $this->service->update($activation, $as2->getKey(), 'value21');
        $as3 = $this->service->create($activation, 'key3', 'value3');
        $this->service->delete($activation, 'key3');

        $settings = $this->service->getAll($activation);

        $this->assertContains($as1, $settings);
        $this->assertContains($as21, $settings);
        $this->assertNotContains($as3, $settings);
        $this->assertNotContains($as2, $settings);
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
