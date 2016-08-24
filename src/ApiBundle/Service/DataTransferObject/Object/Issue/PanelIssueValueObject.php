<?php
/**
 * @package    ApiBundle\Service\DataTransferObject\Object
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\DataTransferObject\Object\Issue;

use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\User\SimpleUserValueObject;

class PanelIssueValueObject extends BaseValueObject
{
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'assigned_to' => $this->getAssignedTo(),
            'overdue' => $this->getOverdue(),
            'date' => $this->getDueDate(),
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
     * Ответственный по задаче
     *
     * @var SimpleUserValueObject
     */
    protected $assignedTo;


    /**
     * Факт просрочки
     *
     * @var bool
     */
    protected $overdue;

    /**
     * Дата создания
     *
     * @var \DateTime
     */
    protected $dueDate;

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
     * @return \DateTime
     */
    public function getDueDate()
    {
        if (!empty($this->dueDate)) {
            return $this->dueDate->getTimestamp();
        }
        return $this->dueDate;
    }


    /**
     * @return SimpleUserValueObject
     */
    public function getAssignedTo()
    {
        $result = null;
        if ($this->assignedTo['id']) {
            $result = SimpleUserValueObject::fromArray($this->assignedTo);
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function getOverdue()
    {
        return boolval($this->overdue);
    }
}