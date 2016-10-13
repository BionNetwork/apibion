<?php

namespace BiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Argument
 */
class Argument
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $datatype;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var Card
     */
    private $card;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $dimension;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $argumentFilters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
        $this->argumentFilters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set name
     *
     * @param string $name
     *
     * @return Argument
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Argument
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get datatype
     *
     * @return string
     */
    public function getDatatype()
    {
        return $this->datatype;
    }

    /**
     * Set datatype
     *
     * @param string $datatype
     *
     * @return Argument
     */
    public function setDatatype($datatype)
    {
        $this->datatype = $datatype;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Argument
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Argument
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get card
     *
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set card
     *
     * @param Card $card
     *
     * @return Argument
     */
    public function setCard(Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return string
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set dimension
     *
     * @param string $dimension
     *
     * @return Argument
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Add argumentFilter
     *
     * @param \BiBundle\Entity\Argument $argumentFilter
     *
     * @return Argument
     */
    public function addArgumentFilter(\BiBundle\Entity\ArgumentFilter $argumentFilter)
    {
        $this->argumentFilters[] = $argumentFilter;

        return $this;
    }

    /**
     * Remove argumentFilter
     *
     * @param \BiBundle\Entity\ArgumentFilter $argumentFilter
     */
    public function removeArgumentFilter(\BiBundle\Entity\ArgumentFilter $argumentFilter)
    {
        $this->argumentFilters->removeElement($argumentFilter);
    }

    /**
     * Get argumentFilters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArgumentFilters()
    {
        return $this->argumentFilters;
    }
}
