<?php

namespace ApiBundle\Service\DataTransferObject\Object\User;

use ApiBundle\Service\DataTransferObject\Object\BaseValueObject;

class OrganizationUserValueObject extends BaseValueObject
{
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'photo' => $this->getPhoto(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'middle_name' => $this->getMiddleName(),
            'organization' => $this->getOrganization(),
            'position' => $this->getPosition(),
        ];
    }

    /**
     * Идентификатор пользователя
     *
     * @var int
     */
    protected $id;

    /**
     * Превью фото
     *
     * @var string
     */
    protected $photo;

    /**
     * Имя
     *
     * @var string
     */
    protected $first_name;
    /**
     * Фамилия
     *
     * @var string
     */
    protected $last_name;
    /**
     * Отчество
     *
     * @var string
     */
    protected $middle_name;

    /**
     * @var string
     */
    protected $position;

    /**
     * @var string
     */
    protected $organization;


    /**
     * @var string
     */
    protected $role;

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
    public function getPhoto()
    {
        return $this->photo;
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
