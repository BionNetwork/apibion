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
    private $cardCarouselImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cardCarouselImage = new ArrayCollection();
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
     * Add cardCarouselImage
     *
     * @param \BiBundle\Entity\CardCarouselImage $cardCarouselImage
     *
     * @return File
     */
    public function addCardCarouselImage(\BiBundle\Entity\CardCarouselImage $cardCarouselImage)
    {
        $this->cardCarouselImage[] = $cardCarouselImage;

        return $this;
    }

    /**
     * Remove cardCarouselImage
     *
     * @param \BiBundle\Entity\CardCarouselImage $cardCarouselImage
     */
    public function removeCardCarouselImage(\BiBundle\Entity\CardCarouselImage $cardCarouselImage)
    {
        $this->cardCarouselImage->removeElement($cardCarouselImage);
    }

    /**
     * Get cardCarouselImage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardCarouselImage()
    {
        return $this->cardCarouselImage;
    }
}
