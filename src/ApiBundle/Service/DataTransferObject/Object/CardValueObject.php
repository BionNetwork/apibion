<?php

namespace ApiBundle\Service\DataTransferObject\Object;

class CardValueObject implements \JsonSerializable
{
    /**
     * Идентификатор карточки
     *
     * @var int
     */
    protected $id;

    /**
     * Название карточки
     *
     * @var string
     */
    protected $name;
    /**
     * Краткое описание карточки
     *
     * @var string
     */
    protected $description;
    /**
     * Полное описание карточки
     *
     * @var string
     */
    protected $descriptionLong;

    /**
     * Дата регистрации в системе
     *
     * @var \DateTime
     */
    protected $createdOn;
    /**
     * Рейтинг
     *
     * @var float
     */
    protected $rating;
    /**
     * Автор
     *
     * @var string
     */
    protected $author;
    /**
     * Фото
     *
     * @var string
     */
    protected $image;
    /**
     * Фото для карусели
     *
     * @var string
     */
    protected $carousel;
    /**
     * Цена
     *
     * @var float
     */
    protected $price;
    /**
     * @var array
     */
    protected $arguments;
    /**
     * @var array
     */
    protected $representations;

    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'description_long' => $this->getDescriptionLong(),
            'rating' => $this->getRating(),
            'author' => $this->getAuthor(),
            'image' => $this->getImage(),
            'carousel' => $this->getCarousel(),
            'created_on' => $this->getCreatedOn(),
            'arguments' => $this->getArguments(),
            'representations' => $this->getRepresentations()
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
        if (!empty($this->createdOn)) {
            return $this->createdOn->getTimestamp();
        }
        return $this->createdOn;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDescriptionLong()
    {
        return $this->descriptionLong;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getRepresentations()
    {
        return $this->representations;
    }
}