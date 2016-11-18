<?php

namespace BiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * File
 */
class File
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $path;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cardImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cardImage = new ArrayCollection();
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
     * Set path
     *
     * @param string $path
     *
     * @return File
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
     * Add cardImage
     *
     * @param \BiBundle\Entity\CardImage $cardImage
     *
     * @return File
     */
    public function addCardImage(\BiBundle\Entity\CardImage $cardImage)
    {
        $this->cardImage[] = $cardImage;

        return $this;
    }

    /**
     * Remove cardImage
     *
     * @param \BiBundle\Entity\CardImage $cardImage
     */
    public function removeCardImage(\BiBundle\Entity\CardImage $cardImage)
    {
        $this->cardImage->removeElement($cardImage);
    }

    /**
     * Get cardCarouselImage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardImage()
    {
        return $this->cardImage;
    }
}
