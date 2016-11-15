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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $arguments;
    /**
     * @var \BiBundle\Entity\FilterType
     */
    private $filterType;
    /**
     * @var \BiBundle\Entity\Card
     */
    private $card;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->arguments = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
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
     * Get filter's type
     *
     * @return \BiBundle\Entity\FilterType
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * Set filterType
     *
     * @param \BiBundle\Entity\FilterType $filterType
     *
     * @return ArgumentFilter
     */
    public function setFilterType(\BiBundle\Entity\FilterType $filterType = null)
    {
        $this->filterType = $filterType;

        return $this;
    }

    /**
     * Get card
     *
     * @return \BiBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set card
     *
     * @param \BiBundle\Entity\Card $card
     *
     * @return ArgumentFilter
     */
    public function setCard(\BiBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }
}
