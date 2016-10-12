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
     * @var \BiBundle\Entity\User
     */
    private $user;

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
     * @var integer
     */
    private $remoteId;

    /**
     * @var array
     */
    private $settings;

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

    /**
     * Set user
     *
     * @param \BiBundle\Entity\User $user
     *
     * @return Resource
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
     * Set remoteId
     *
     * @param integer $remoteId
     *
     * @return Resource
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    /**
     * Get remoteId
     *
     * @return integer
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * Set settings
     *
     * @param array $settings
     *
     * @return Resource
     */
    public function setSettings(array $settings)
    {
        $this->settings = json_encode($settings);

        return $this;
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        return json_decode($this->settings, true);
    }

    /**
     * Adds file to settings
     *
     * @param $type
     * @param $path
     */
    public function addFile($type, $path)
    {
        $settings = $this->getSettings();
        if (!$settings) {
            $settings = [];
        }
        $settings['type'] = $type;
        $settings['file'] = ['path' => $path];
        $this->setSettings($settings);
    }
}
