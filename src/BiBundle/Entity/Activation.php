<?php

namespace BiBundle\Entity;

/**
 * Activation
 */
class Activation
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
     * @var \BiBundle\Entity\Card
     */
    private $card;

    /**
     * @var \BiBundle\Entity\User
     */
    private $user;


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
     * @return Activation
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
     * @return Activation
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
     * Set card
     *
     * @param \BiBundle\Entity\Card $card
     *
     * @return Activation
     */
    public function setCard(\BiBundle\Entity\Card $card)
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
     * Set user
     *
     * @param \BiBundle\Entity\User $user
     *
     * @return Activation
     */
    public function setUser(\BiBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \BiBundle\Entity\ActivationStatus
     */
    private $activation_status;


    /**
     * Set activationStatus
     *
     * @param \BiBundle\Entity\ActivationStatus $activationStatus
     *
     * @return Activation
     */
    public function setActivationStatus(\BiBundle\Entity\ActivationStatus $activationStatus)
    {
        $this->activation_status = $activationStatus;

        return $this;
    }

    /**
     * Get activationStatus
     *
     * @return \BiBundle\Entity\ActivationStatus
     */
    public function getActivationStatus()
    {
        return $this->activation_status;
    }
}
