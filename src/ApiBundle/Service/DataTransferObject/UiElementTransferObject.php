<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\UiElement;

class UiElementTransferObject
{
    /**
     * Get argument's data normalized
     *
     * @param UiElement $uiElement
     * @return array
     */
    public function getObjectData(UiElement $uiElement)
    {
        if ($uiElement->getChildren()->count() > 0) {
            $r = [];
            foreach ($uiElement->getChildren() as $childElement) {
                $r[] = $this->getObjectData($childElement);
            }
            return [$uiElement->getName() => call_user_func_array('array_merge', $r)];
        } else {
            return [$uiElement->getName() => $uiElement->getValue()];
        }
    }

    /**
     * Get argument list
     *
     * @param \BiBundle\Entity\UiElement[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $argument) {
            $item = $this->getObjectData($argument);
            $result[] = $item;
        }
        return $result;
    }
}