<?php

namespace ApiBundle\Service\DataTransferObject\Object;

use BiBundle\Entity\User;
use BiBundle\Service\Utils\HostBasedUrl;

class DashboardCardValueObject implements \JsonSerializable
{
    /**
     * @var HostBasedUrl
     */
    protected $url;

    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'created_on' => $this->getCreatedOn(),
        ];
    }

    /**
     * @param User|null $user
     * @return array|null
     */
    protected function getUser(User $user = null)
    {
        if (null === $user) {
            return null;
        }
        $organization = $user->getOrganization();
        return [
            'id' => $user->getId(),
            'first_name' => $user->getFirstname(),
            'last_name' => $user->getLastname(),
            'middle_name' => $user->getMiddlename(),
        ];
    }

    /**
     * Create object
     *
     * @param array $data
     * @param HostBasedUrl $url
     * @return UserValueObject
     */
    public static function fromArray(array $data, HostBasedUrl $url)
    {
        $object = new self();
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
        $object->url = $url;
        return $object;
    }

    /**
     * Идентификатор проекта
     *
     * @var int
     */
    protected $id;

    /**
     * Краткое наименование проекта
     *
     * @var string
     */
    protected $name;

    /**
     * Дата регистрации в системе
     *
     * @var \DateTime
     */
    protected $created_on;

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
    public function getCreatedOn()
    {
        if (!empty($this->created_on)) {
            return $this->created_on->getTimestamp();
        }
        return $this->created_on;
    }
}