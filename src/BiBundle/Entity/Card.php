<?php

namespace BiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 */
class Card
{
    /**
     * @var Collection
     */
    private $purchase;

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
    private $type;

    /**
     * @var string
     */
    private $price;

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
    private $description;

    /**
     * @var string
     */
    private $description_long;

    /**
     * @var string
     */
    private $rating;

    /**
     * @var string
     */
    private $image;

    /**
     * @var Collection
     */
    private $cardRepresentation;

    /**
     * @var string
     */
    private $carousel;

    /**
     * @var Collection
     */
    private $argument;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $locale;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchase = new ArrayCollection();
        $this->cardRepresentation = new ArrayCollection();
        $this->argument = new ArrayCollection();
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
     * @return Card
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
     * Set type
     *
     * @param string $type
     *
     * @return Card
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Card
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Card
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
     * @return Card
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
     * Add purchase
     *
     * @param \BiBundle\Entity\Purchase $purchase
     *
     * @return Card
     */
    public function addPurchase(\BiBundle\Entity\Purchase $purchase)
    {
        $this->purchase[] = $purchase;
        $purchase->setCard($this);

        return $this;
    }

    /**
     * Remove purchase
     *
     * @param \BiBundle\Entity\Purchase $purchase
     */
    public function removePurchase(\BiBundle\Entity\Purchase $purchase)
    {
        $this->purchase->removeElement($purchase);
    }

    /**
     * Get purchase
     *
     * @return Collection
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Card
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionLong
     *
     * @param string $descriptionLong
     *
     * @return Card
     */
    public function setDescriptionLong($descriptionLong)
    {
        $this->description_long = $descriptionLong;

        return $this;
    }

    /**
     * Get descriptionLong
     *
     * @return string
     */
    public function getDescriptionLong()
    {
        return $this->description_long;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Card
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Card
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add cardRepresentation
     *
     * @param \BiBundle\Entity\CardRepresentation $cardRepresentation
     *
     * @return Card
     */
    public function addCardRepresentation(\BiBundle\Entity\CardRepresentation $cardRepresentation)
    {
        $this->cardRepresentation[] = $cardRepresentation;
        $cardRepresentation->setCard($this);

        return $this;
    }

    /**
     * Remove cardRepresentation
     *
     * @param \BiBundle\Entity\CardRepresentation $cardRepresentation
     */
    public function removeCardRepresentation(\BiBundle\Entity\CardRepresentation $cardRepresentation)
    {
        $this->cardRepresentation->removeElement($cardRepresentation);
    }

    /**
     * Get cardRepresentation
     *
     * @return Collection
     */
    public function getCardRepresentation()
    {
        return $this->cardRepresentation;
    }

    /**
     * Set carousel
     *
     * @param string $carousel
     *
     * @return Card
     */
    public function setCarousel($carousel)
    {
        $this->carousel = $carousel;

        return $this;
    }

    /**
     * Get carousel
     *
     * @return string
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

    /**
     * Add argument
     *
     * @param \BiBundle\Entity\Argument $argument
     *
     * @return Card
     */
    public function addArgument(\BiBundle\Entity\Argument $argument)
    {
        $this->argument[] = $argument;
        $argument->setCard($this);
        return $this;
    }

    /**
     * Remove argument
     *
     * @param \BiBundle\Entity\Argument $argument
     */
    public function removeArgument(\BiBundle\Entity\Argument $argument)
    {
        $this->argument->removeElement($argument);
    }

    /**
     * Get argument
     *
     * @return Collection
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Card
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
