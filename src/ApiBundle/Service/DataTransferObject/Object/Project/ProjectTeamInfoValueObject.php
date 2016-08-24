<?php

namespace ApiBundle\Service\DataTransferObject\Object\Project;


use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\SearchOrganizationValueObject;
use ApiBundle\Service\DataTransferObject\Object\User\OrganizationUserValueObject;

class ProjectTeamInfoValueObject extends BaseValueObject
{

    function jsonSerialize()
    {
        return [
            'items' => $this->getItems(),
            'items_total' => $this->getItemsTotal(),
        ];
    }

    /**
     * @var ProjectTeamInfoItemValueObject[]
     */
    protected $items;


    /**
     * @return ProjectTeamInfoItemValueObject[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ProjectTeamInfoItemValueObject $item
     */
    public function addItem(ProjectTeamInfoItemValueObject $item)
    {
        $this->items[] = $item;
    }


    /**
     * @return int
     */
    public function getItemsTotal()
    {
        return count($this->getItems());
    }


}