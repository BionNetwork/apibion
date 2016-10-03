<?php

namespace BiBundle\Entity;

/**
 * Representation
 */
class Representation
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
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $card;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->card = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
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
     * @return Representation
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
     * Set code
     *
     * @param string $code
     *
     * @return Representation
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Representation
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

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
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Representation
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

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
     * Add card
     *
     * @param \BiBundle\Entity\Card $card
     *
     * @return Representation
     */
    public function addCard(\BiBundle\Entity\Card $card)
    {
        $this->card[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \BiBundle\Entity\Card $card
     */
    public function removeCard(\BiBundle\Entity\Card $card)
    {
        $this->card->removeElement($card);
    }

    /**
     * Get card
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCard()
    {
        return $this->card;
    }
}
