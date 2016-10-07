<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\CardCategory;

class CardCategoryTransferObject
{
    /**
     * Get category's data normalized
     *
     * @param CardCategory $cardCategory
     * @return array
     */
    public function getObjectData(CardCategory $cardCategory)
    {
        $data = [
            'id' => $cardCategory->getId(),
            'name' => $cardCategory->getName(),
            'path' => $cardCategory->getPath(),
        ];
        return $data;
    }

    /**
     * Get category list
     *
     * @param \BiBundle\Entity\CardCategory[] $data
     * @return array
     */
    public function getObjectListData(array $data)
    {
        $result = [];

        foreach ($data as $cardCategory) {
            $result[] = $this->getObjectData($cardCategory);
        }
        return $result;
    }

    /**
     * Get category list
     *
     * @param array $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $category) {
            $item = [
                'id' => $category['id'],
                'name' => $category['name'],
                'path' => $category['path'],
            ];
            $result[] = $item;
        }
        return $result;
    }
}