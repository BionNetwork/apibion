<?php

namespace BiBundle\Entity;

/**
 * ArgumentFilter
 */
class ArgumentFilter
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $filterType;


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
     * Set label
     *
     * @param string $label
     *
     * @return ArgumentFilter
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set filterType
     *
     * @param string $filterType
     *
     * @return ArgumentFilter
     */
    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;

        return $this;
    }

    /**
     * Get filterType
     *
     * @return string
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $arguments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->arguments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add argument
     *
     * @param \BiBundle\Entity\Argument $argument
     *
     * @return ArgumentFilter
     */
    public function addArgument(\BiBundle\Entity\Argument $argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Remove argument
     *
     * @param \BiBundle\Entity\Argument $argument
     */
    public function removeArgument(\BiBundle\Entity\Argument $argument)
    {
        $this->arguments->removeElement($argument);
    }

    /**
     * Get arguments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @var \BiBundle\Entity\FilterControlType
     */
    private $filterControlType;


    /**
     * Set filterControlType
     *
     * @param \BiBundle\Entity\FilterControlType $filterControlType
     *
     * @return ArgumentFilter
     */
    public function setFilterControlType(\BiBundle\Entity\FilterControlType $filterControlType = null)
    {
        $this->filterControlType = $filterControlType;

        return $this;
    }

    /**
     * Get filterControlType
     *
     * @return \BiBundle\Entity\FilterControlType
     */
    public function getFilterControlType()
    {
        return $this->filterControlType;
    }
}
