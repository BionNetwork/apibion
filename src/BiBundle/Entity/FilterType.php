<?php

namespace BiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("name")
 */
class FilterType
{
    const TYPE_SLIDEBAR = 'slidebar';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_COMBOBOX = 'combobox';
    const TYPE_CALENDAR = 'calendar';
    const TYPE_RADIOBUTTON = 'radiobutton';
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $sort = 0;

    /**
     * @var string
     */
    private $type;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FilterType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return FilterType
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return FilterType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
