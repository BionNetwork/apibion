<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Argument;
use Doctrine\Common\Collections\ArrayCollection;

class ArgumentTransferObject
{
    /**
     * Get argument's data normalized
     *
     * @param Argument $argument
     * @return array
     */
    public static function getObjectData(Argument $argument)
    {
        $data = [
            'id' => $argument->getId(),
            'name' => $argument->getName(),
            'code' => $argument->getCode(),
            'dimension' => $argument->getDimension(),
            'filter_control_type' => $argument->getFilterControlType() ? $argument->getFilterControlType()->getName() : null,
        ];
        return $data;
    }

    /**
     * Get argument list
     *
     * @param ArrayCollection $data
     * @return array
     */
    public function getObjectListData($data)
    {
        $result = [];

        foreach ($data as $argument) {
            $item = $this->getObjectData($argument);
            $result[] = $item;
        }
        return $result;
    }
}