<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\ActivationSetting;

class ActivationSettingTransferObject
{
    /**
     * Get argument's data normalized
     *
     * @param ActivationSetting $activationSetting
     * @return array
     */
    public static function getObjectData(ActivationSetting $activationSetting)
    {
        return [$activationSetting->getKey() => $activationSetting->getValue()];
    }

    /**
     * Get argument list
     *
     * @param \BiBundle\Entity\ActivationSetting[] $data
     * @return array
     */
    public static function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $activationSetting) {
            $result[$activationSetting->getKey()] = $activationSetting->getValue();
        }
        return $result;
    }
}