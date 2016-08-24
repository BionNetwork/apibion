<?php

namespace ApiBundle\Service\DataTransferObject\Object\Project;


use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\SearchOrganizationValueObject;
use ApiBundle\Service\DataTransferObject\Object\User\OrganizationUserValueObject;

class ProjectTeamInfoItemValueObject extends BaseValueObject
{


    function jsonSerialize()
    {
        return [
            'assigned_to' => $this->getAssignedTo(),
            'issues' => $this->getIssues(),
            'stats' => $this->getStats(),
        ];
    }

    /**
     * @var OrganizationUserValueObject
     */
    protected $assignedTo;

    /**
     * @var int
     */
    protected $issues;

    /**
     * @var ProjectTeamInfoStatValueObject[]
     */
    protected $stats;

    /**
     * @return OrganizationUserValueObject
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * @return int
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @return ProjectTeamInfoStatValueObject[]
     */
    public function getStats()
    {
        return $this->stats;
    }

}