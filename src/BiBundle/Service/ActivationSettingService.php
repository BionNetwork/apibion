<?php

namespace BiBundle\Service;

use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Repository\ActivationSettingRepository;
use BiBundle\Service\Exception\ActivationSettingException;

class ActivationSettingService
{
    /** @var  ActivationSettingRepository */
    private $repository;

    /**
     * ActivationSettingService constructor.
     */
    public function __construct(ActivationSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null
     */
    public function get(Activation $activation, $key)
    {
        $activationSetting = $this->repository->getLatestActualByKey($activation, $key);
        if (!$activationSetting) {
            throw new ActivationSettingException("Key '$key' for activation {$activation->getId()} doesn't exist");
        }

        return $activationSetting;
    }

    /**
     * @param Activation $activation
     * @return ActivationSetting[]|null
     */
    public function getAll(Activation $activation)
    {
        return $this->repository->getLatestActualForAll($activation);
    }

    /**
     * @param Activation $activation
     * @param $key
     * @param $value
     * @return ActivationSetting
     * @throws ActivationSettingException
     */
    public function create(Activation $activation, $key, $value)
    {
        if ($this->repository->keyExists($activation, $key)) {
            throw new ActivationSettingException("Key '$key' already exists for activation {$activation->getId()}");
        }
        $activationSetting = $this->createActivationSetting($activation, $key, $value);
        $this->repository->save($activationSetting);

        return $activationSetting;
    }

    /**
     * @param Activation $activation
     * @param $key
     * @param $value
     * @throws ActivationSettingException
     */
    public function update(Activation $activation, $key, $value)
    {
        if (!$this->repository->keyExists($activation, $key)) {
            throw new ActivationSettingException("Setting '$key' for activation {$activation->getId()} doesn't exist");
        }
        $this->repository->purgeSoftDeletes($activation, $key);
        $this->repository->save($this->createActivationSetting($activation, $key, $value));
    }

    /**
     * @param Activation $activation
     * @param $key
     * @throws ActivationSettingException
     */
    public function delete(Activation $activation, $key)
    {
        if (!$this->repository->keyExists($activation, $key)) {
            throw new ActivationSettingException("Setting '$key' for activation {$activation->getId()} doesn't exist");
        }
        $this->repository->deleteByKey($activation, $key);
    }

    /**
     * Undo for key and return current actual ActivationSetting
     *
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null
     * @throws ActivationSettingException
     */
    public function undo(Activation $activation, $key)
    {
        $history = $this->repository->getActualByKey($activation, $key);
        if (count($history) <= 1) {
            throw new ActivationSettingException("Unable to undo, active history length is " . count($history));
        }
        $this->repository->softDelete($this->repository->getLatestActualByKey($activation, $key));

        return $this->repository->getLatestActualByKey($activation, $key);
    }

    /**
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null
     * @throws ActivationSettingException
     */
    public function redo(Activation $activation, $key)
    {
        $redoElement = $this->repository->getRedoElementByKey($activation, $key);
        if ($redoElement) {
            $this->repository->clearSoftDelete($redoElement);
        } else {
            throw new ActivationSettingException("Key '$key' has no elements to redo");
        }

        return $this->repository->getLatestActualByKey($activation, $key);
    }

    /**
     * @param Activation $activation
     * @param $key
     * @param $value
     * @return ActivationSetting
     */
    private function createActivationSetting(Activation $activation, $key, $value)
    {
        $activationSetting = new ActivationSetting();
        $activationSetting->setKey($key);
        $activationSetting->setValue($value);
        $activationSetting->setActivation($activation);

        return $activationSetting;
    }
}