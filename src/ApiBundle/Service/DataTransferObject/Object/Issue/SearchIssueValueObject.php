<?php
/**
 * @package    ApiBundle\Service\DataTransferObject\Object
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\DataTransferObject\Object\Issue;

use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;
use ApiBundle\Service\DataTransferObject\Object\UserValueObject;

class SearchIssueValueObject extends BaseValueObject
{
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'project' => $this->getProject(),
            'assigned_to' => $this->getAssignedTo(),
            'author' => $this->getAuthor(),
            'status' => $this->getStatus(),
            'parent' => $this->getParent(),
            'start_date' => $this->getStartDate(),
            'due_date' => $this->getDueDate(),
            'created_on' => $this->getCreatedOn(),
            'started_on' => $this->getStartedOn(),
            'closed_on' => $this->getClosedOn(),
            'level' => $this->getLevel(),
            'has_children' => $this->getHasChildren(),
//            'duration_fact' => $this->getDurationFact()
        ];
    }


    /**
     * Идентификатор задачи
     * @var int
     */
    protected $id;

    /**
     * Наименование задачи
     * @var string
     */
    protected $name;

    /**
     * Описание задачи
     * @var string
     */
    protected $description;

    /**
     * Дата начала (план)
     * @var \DateTime
     */
    protected $startDate;

    /**
     * Дата окончания (план)
     * @var \DateTime
     */
    protected $dueDate;

    /**
     * Дата создания
     * @var \DateTime
     */
    protected $createdOn;
    /**
     * Дата начала (факт)
     * @var \DateTime
     */
    protected $startedOn;

    /**
     * Дата закрытия
     * @var \DateTime
     */
    protected $closedOn;

    /**
     * Родительская задача
     *
     * @var array
     */
    protected $parent;

    /**
     * Проект
     *
     * @var array
     */
    protected $project;


    /**
     * Статус задачи
     *
     * @var string
     */
    protected $status;

    /**
     * Приоритет задачи
     *
     * @var array
     */
    protected $priority;

    /**
     * Автор задачи
     *
     * @var array
     */
    protected $author;

    /**
     * Ответственный по задаче
     *
     * @var array
     */
    protected $assignedTo;

    /**
     * Уровень вложенности нода
     * @var int
     */
    protected $level;

    /**
     * Есть ли дети
     * @var int
     */
    protected $hasChildren;
    /**
     * Длительность по факту
     *
     * @var int
     */
    protected $durationFact;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        if (!empty($this->startDate)) {
            return $this->startDate->getTimestamp();
        }
        return $this->startDate;
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
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        if (!empty($this->createdOn)) {
            return $this->createdOn->getTimestamp();
        }
        return $this->createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getClosedOn()
    {
        if (!empty($this->closedOn)) {
            return $this->closedOn->getTimestamp();
        }
        return $this->closedOn;
    }

    /**
     * @return array
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function getProject()
    {
        $result = null;
        if ($this->project) {
            $result = [
                'id' => $this->project['id'],
                'name' => $this->project['name']
            ];
        }
        return $result;
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return array
     */
    public function getPriority()
    {
        return $this->priority;
    }


    /**
     * @return array
     */
    public function getAuthor()
    {
        $result = null;
        if ($this->author) {
            $result = UserValueObject::fromArray($this->author);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getAssignedTo()
    {
        $result = null;
        if ($this->assignedTo) {
            $result = UserValueObject::fromArray($this->assignedTo);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getHasChildren()
    {
        return !empty($this->hasChildren);
    }

    /**
     * @return int
     */
    public function getDurationFact()
    {
        return $this->durationFact;
    }

    /**
     * @return \DateTime
     */
    public function getStartedOn()
    {
        if (!empty($this->startedOn)) {
            return $this->startedOn->getTimestamp();
        }
        return null;
    }
}
