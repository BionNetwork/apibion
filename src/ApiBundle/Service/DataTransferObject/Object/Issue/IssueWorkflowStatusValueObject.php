<?php
namespace ApiBundle\Service\DataTransferObject\Object\Issue;


use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;

class IssueWorkflowStatusValueObject extends BaseValueObject
{
    protected $id;
    protected $name;

    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


}