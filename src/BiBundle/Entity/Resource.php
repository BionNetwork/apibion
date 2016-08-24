<?php

namespace BiBundle\Entity;

/**
 * Resource
 */
class Resource
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
     * @var string
     */
    private $path;

    /**
     * @var \BiBundle\Entity\DashboardCard
     */
    private $dashboard_card;

    /**
     * @var \BiBundle\Entity\Activation
     */
    private $activation;

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
     * @return Resource
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
     * @return Resource
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
     * Set path
     *
     * @param string $path
     *
     * @return Resource
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set dashboardCard
     *
     * @param \BiBundle\Entity\DashboardCard $dashboardCard
     *
     * @return Resource
     */
    public function setDashboardCard(\BiBundle\Entity\DashboardCard $dashboardCard)
    {
        $this->dashboard_card = $dashboardCard;

        return $this;
    }

    /**
     * Get dashboardCard
     *
     * @return \BiBundle\Entity\DashboardCard
     */
    public function getDashboardCard()
    {
        return $this->dashboard_card;
    }

    /**
     * Set activation
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return Resource
     */
    public function setActivation(\BiBundle\Entity\Activation $activation)
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
}
