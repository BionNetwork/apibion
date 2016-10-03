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
        return $this->repository->getLatestActualByKey($activation, $key);
    }

    /**
     * @param Activation $activation
     * @return ActivationSetting[]|null
     */
    public function getAll(Activation $activation)
    {
        return $this->repository->getLatestActualForAll($activation);
    }

    public function create(Activation $activation, $key, $value)
    {
        $this->repository->save($this->createActivationSetting($activation, $key, $value));
    }

    public function update(Activation $activation, $key, $value)
    {
        if (!$this->repository->keyExists($activation, $key)) {
            throw new ActivationSettingException("Setting '$key' for activation {$activation->getId()} doesn't exist");
        }
        $this->repository->purgeSoftDeletes($activation, $key);
        $this->repository->save($this->createActivationSetting($activation, $key, $value));
    }

    public function delete(Activation $activation, $key)
    {
        if ($this->repository->keyExists($activation, $key)) {
            throw new ActivationSettingException("Setting '$key' for activation {$activation->getId()} doesn't exist");
        }
        $this->repository->deleteByKey($activation, $key);
    }

    /**
     * Undo for key and return current actual ActivationSetting
     *
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null|object
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

    public function redo(Activation $activation, $key)
    {
        $redoElement = $this->repository->getRedoElementByKey($activation, $key);
        if ($redoElement) {
            throw new ActivationSettingException("Key '$key' has no elements to redo");
        } else {
            $this->repository->clearSoftDelete($redoElement);
        }

        return $this->repository->getLatestActualByKey($activation, $key);
    }

    private function createActivationSetting(Activation $activation, $key, $value)
    {
        $activationSetting = new ActivationSetting();
        $activationSetting->setKey($key);
        $activationSetting->setValue($value);
        $activationSetting->setActivation($activation);

        return $activationSetting;
    }
}