<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\Chart;

class RepresentationTransferObject
{
    /**
     * Get representation's data normalized
     *
     * @param Chart $chart
     * @return array
     */
    public function getObjectData(Chart $chart)
    {
        $data = [
            'id' => $chart->getId(),
            'name' => $chart->getName(),
            'code' => $chart->getCode(),
        ];
        return $data;
    }

    /**
     * Get charts list
     *
     * @param $data
     * @return array
     */
    public function getObjectListData($data)
    {
        $result = [];

        foreach ($data as $chart) {
            $item = $this->getObjectData($chart);
            $result[] = $item;
        }
        return $result;
    }
}