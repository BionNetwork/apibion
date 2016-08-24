<?php
/**
 * @package    ApiBundle\Service\DataTransferObject\Object
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\DataTransferObject\Object;

class UserValueObject implements \JsonSerializable
{
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'avatar' => $this->getAvatar(),
            'avatar_small' => $this->getAvatarSmall(),
            'birth_date' => $this->getBirthDate(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'middle_name' => $this->getMiddleName(),
            'login' => $this->getLogin(),
            'phone' => $this->getPhone(),
            'phones' => $this->getPhones(),
            'position' => $this->getPosition(),
            'organizations' => $this->getOrganizations()
        ];
    }

    /**
     * Create object
     *
     * @param array $data
     * @return UserValueObject
     */
    public static function fromArray(array $data)
    {
        $object = new self();
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }

    /**
     * Идентификатор пользователя
     *
     * @var int
     */
    protected $id;
    /**
     * Фото
     *
     * @var string
     */
    protected $avatar;
    /**
     * Превью фото
     *
     * @var string
     */
    protected $avatar_small;
    /**
     * Дата рождения
     *
     * @var \DateTime
     */
    protected $birth_date;
    /**
     * Email
     *
     * @var string
     */
    protected $email;
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
     * Логин
     *
     * @var string
     */
    protected $login;
    /**
     * Основной телефон
     *
     * @var string
     */
    protected $phone;
    /**
     * Дополнительные телефоны
     *
     * @var array
     */
    protected $phones;
    /**
     * Должность
     *
     * @var string
     */
    protected $position;

    /**
     * Организации
     *
     * @var array
     */
    protected $organizations;

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
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getAvatarSmall()
    {
        return $this->avatar_small;
    }

    /**
     * @return int
     */
    public function getBirthDate()
    {
        if (!empty($this->birth_date)) {
            return $this->birth_date->getTimestamp();
        }
        return $this->birth_date;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return array
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

}
