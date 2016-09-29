<?php

namespace BiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * UiElement
 */
class UiElement
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
    private $value;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \BiBundle\Entity\UiElement
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return UiElement
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
     * Set value
     *
     * @param string $value
     *
     * @return UiElement
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add child
     *
     * @param \BiBundle\Entity\UiElement $child
     *
     * @return UiElement
     */
    public function addChild(\BiBundle\Entity\UiElement $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \BiBundle\Entity\UiElement $child
     */
    public function removeChild(\BiBundle\Entity\UiElement $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \BiBundle\Entity\UiElement $parent
     *
     * @return UiElement
     */
    public function setParent(\BiBundle\Entity\UiElement $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \BiBundle\Entity\UiElement
     */
    public function getParent()
    {
        return $this->parent;
    }
}

