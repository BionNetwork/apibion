<?php

namespace BiBundle\Entity;

/**
 * DashboardActivation
 */
class DashboardActivation
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
     * @var \BiBundle\Entity\Dashboard
     */
    private $dashboard;

    /**
     * @var \BiBundle\Entity\Activation
     */
    private $activation;

    /**
     * @var \BiBundle\Entity\User
     */
    private $user;

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
     * @return DashboardActivation
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
     * @return DashboardActivation
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
     * Set dashboard
     *
     * @param \BiBundle\Entity\Dashboard $dashboard
     *
     * @return DashboardActivation
     */
    public function setDashboard(\BiBundle\Entity\Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        return $this;
    }

    /**
     * Get dashboard
     *
     * @return \BiBundle\Entity\Dashboard
     */
    public function getDashboard()
    {
        return $this->dashboard;
    }

    /**
     * Set activation
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return DashboardActivation
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

    /**
     * Set user
     *
     * @param \BiBundle\Entity\User $user
     *
     * @return DashboardActivation
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
}
