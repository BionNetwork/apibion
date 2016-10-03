<?php

namespace BiBundle\Entity;

/**
 * Image
 */
class Image
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
    private $path;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var string
     */
    private $cropData;

    /**
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var boolean
     */
    private $queued = false;

    /**
     * @var boolean
     */
    private $published = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sizes;

    /**
     * @var \BiBundle\Entity\User
     */
    private $createdBy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sizes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Image
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
     * Set path
     *
     * @param string $path
     *
     * @return Image
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
     * Set size
     *
     * @param integer $size
     *
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set mime
     *
     * @param string $mime
     *
     * @return Image
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set cropData
     *
     * @param string $cropData
     *
     * @return Image
     */
    public function setCropData($cropData)
    {
        $this->cropData = $cropData;

        return $this;
    }

    /**
     * Get cropData
     *
     * @return string
     */
    public function getCropData()
    {
        return $this->cropData;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Image
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
     * @return Image
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
     * Set queued
     *
     * @param boolean $queued
     *
     * @return Image
     */
    public function setQueued($queued)
    {
        $this->queued = $queued;

        return $this;
    }

    /**
     * Get queued
     *
     * @return boolean
     */
    public function getQueued()
    {
        return $this->queued;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Image
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Add size
     *
     * @param \BiBundle\Entity\ImageSize $size
     *
     * @return Image
     */
    public function addSize(\BiBundle\Entity\ImageSize $size)
    {
        $this->sizes[] = $size;

        return $this;
    }

    /**
     * Remove size
     *
     * @param \BiBundle\Entity\ImageSize $size
     */
    public function removeSize(\BiBundle\Entity\ImageSize $size)
    {
        $this->sizes->removeElement($size);
    }

    /**
     * Get sizes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * Set createdBy
     *
     * @param \BiBundle\Entity\User $createdBy
     *
     * @return Image
     */
    public function setCreatedBy(\BiBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \BiBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}

