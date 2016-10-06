<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Representation;

class RepresentationTransferObject
{
    /**
     * Get representation's data normalized
     *
     * @param Representation $representation
     * @return array
     */
    public function getObjectData(Representation $representation)
    {
        $data = [
            'id' => $representation->getId(),
            'name' => $representation->getName(),
            'code' => $representation->getCode(),
        ];
        return $data;
    }

    /**
     * Get representations list
     *
     * @param $data
     * @return array
     */
    public function getObjectListData($data)
    {
        $result = [];

        foreach ($data as $representation) {
            $item = $this->getObjectData($representation);
            $result[] = $item;
        }
        return $result;
    }
}