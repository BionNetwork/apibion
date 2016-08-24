<?php

namespace ApiBundle\Service\DataTransferObject\Object\Project;


use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\Issue\IssueWorkflowStatusValueObject;

class ProjectTeamInfoStatValueObject extends BaseValueObject
{
    /**
     * @var string
     */
    protected $status;
    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $overdue;


    function jsonSerialize()
    {
        return [
            'status' => $this->getStatus(),
            'total' => $this->getTotal(),
            'overdue' => $this->getOverdue(),
        ];
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getOverdue()
    {
        return $this->overdue;
    }


}