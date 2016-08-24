<?php

namespace BiBundle\Entity;

/**
 * CardRepresentation
 */
class CardRepresentation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var \BiBundle\Entity\Activation
     */
    private $activation;

    /**
     * @var \BiBundle\Entity\Card
     */
    private $card;

    /**
     * @var \BiBundle\Entity\Representation
     */
    private $representation;


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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return CardRepresentation
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
     * @return CardRepresentation
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
     * Set activation
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return CardRepresentation
     */
    public function setActivation(\BiBundle\Entity\Activation $activation = null)
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * Get activation
     *
     * @return \BiBundle\Entity\Activation
     */
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * Set card
     *
     * @param \BiBundle\Entity\Card $card
     *
     * @return CardRepresentation
     */
    public function setCard(\BiBundle\Entity\Card $card = null)
    {
        $this->card = $card;

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
     * Set representation
     *
     * @param \BiBundle\Entity\Representation $representation
     *
     * @return CardRepresentation
     */
    public function setRepresentation(\BiBundle\Entity\Representation $representation = null)
    {
        $this->representation = $representation;

        return $this;
    }

    /**
     * Get representation
     *
     * @return \BiBundle\Entity\Representation
     */
    public function getRepresentation()
    {
        return $this->representation;
    }
}

