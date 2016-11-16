<?php

namespace BiBundle\Entity;

/**
 * CardChart
 */
class CardChart
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
     * @var \BiBundle\Entity\Chart
     */
    private $chart;


    public function __construct()
    {
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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return CardChart
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
     * @return CardChart
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
     * @return CardChart
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
     * @return CardChart
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
     * Set chart
     *
     * @param \BiBundle\Entity\Chart $chart
     *
     * @return CardChart
     */
    public function setChart(\BiBundle\Entity\Chart $chart = null)
    {
        $this->chart = $chart;

        return $this;
    }

    /**
     * Get chart
     *
     * @return \BiBundle\Entity\Chart
     */
    public function getChart()
    {
        return $this->chart;
    }
}
