<?php

namespace ApiBundle\Service\DataTransferObject\Object\Issue;

use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use BiBundle\Entity\IssueWorkflowStatus;


class IssueClosedOnPeriodValueObject extends BaseValueObject
{
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'status' => $this->getStatus(),
        ];
    }


    /**
     * Идентификатор задачи
     *
     * @var int
     */
    protected $id;

    /**
     * Наименование задачи
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var IssueWorkflowStatus
     */
    protected $status;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return strtolower($this->status->getCode());
    }
}