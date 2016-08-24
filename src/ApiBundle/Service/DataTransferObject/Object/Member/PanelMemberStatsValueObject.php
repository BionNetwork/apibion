<?php

namespace ApiBundle\Service\DataTransferObject\Object\Member;

use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\User\OrganizationUserValueObject;

class PanelMemberStatsValueObject extends BaseValueObject
{
    function jsonSerialize()
    {
        return [
            'total' => $this->getTotal(),
            'assigned_to' => $this->getAssignedTo(),
        ];
    }


    /**
     * Количество участников
     *
     * @var int
     */
    protected $total;

    /**
     * Руководитель проекта
     *
     * @var array
     */
    protected $assignedTo;

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return \OrganizationUserValueObject
     */
    public function getAssignedTo()
    {
        return new OrganizationUserValueObject($this->assignedTo);
    }

}