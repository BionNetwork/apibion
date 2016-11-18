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
     * @var ArrayCollection
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
     * @var ArrayCollection
     */
    private $cardChart;

    /**
     * @var ArrayCollection
     */
    private $argument;

    /**
     * @var string
     */
    private $author;

    /**
     * @var CardCategory
     */
    private $cardCategory;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var Collection
     */
    private $cardImage;

    /**
     * @var File
     */
    private $image;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $argumentFilters;

    /**
     * data of filters and charts chained to card
     *
     * @var array
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchase = new ArrayCollection();
        $this->cardChart = new ArrayCollection();
        $this->argument = new ArrayCollection();
        $this->cardImage = new ArrayCollection();
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
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
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * @return ArrayCollection
     */
    public function getPurchase()
    {
        return $this->purchase;
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
     * Get descriptionLong
     *
     * @return string
     */
    public function getDescriptionLong()
    {
        return $this->description_long;
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
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
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
     * Add card chart
     *
     * @param \BiBundle\Entity\CardChart $cardChart
     *
     * @return Card
     */
    public function addCardChart(\BiBundle\Entity\CardChart $cardChart)
    {
        $this->cardChart[] = $cardChart;
        $cardChart->setCard($this);

        return $this;
    }

    /**
     * Remove card chart
     *
     * @param \BiBundle\Entity\CardChart $cardChart
     */
    public function removeCardChart(\BiBundle\Entity\CardChart $cardChart)
    {
        $this->cardChart->removeElement($cardChart);
    }

    /**
     * Get charts
     *
     * @return ArrayCollection
     */
    public function getCardChart()
    {
        return $this->cardChart;
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
     * @return ArrayCollection
     */
    public function getArgument()
    {
        return $this->argument;
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
     * @return CardCategory
     */
    public function getCardCategory()
    {
        return $this->cardCategory;
    }

    /**
     * @param CardCategory $cardCategory
     */
    public function setCardCategory($cardCategory)
    {
        $this->cardCategory = $cardCategory;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Add cardImage
     *
     * @param CardImage $cardImage
     *
     * @return Card
     */
    public function addCardImage(CardImage $cardImage)
    {
        $this->cardImage[] = $cardImage;

        return $this;
    }

    /**
     * Remove cardImage
     *
     * @param CardImage $cardImage
     */
    public function removeCardImage(CardImage $cardImage)
    {
        $this->cardImage->removeElement($cardImage);
    }

    /**
     * Get cardImage
     *
     * @return Collection
     */
    public function getCardImage()
    {
        return $this->cardImage;
    }

    /**
     * Get imageFile
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set imageFile
     *
     * @param File $image
     *
     * @return Card
     */
    public function setImage(File $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Add argumentFilter
     *
     * @param \BiBundle\Entity\ArgumentFilter $argumentFilter
     *
     * @return Card
     */
    public function addArgumentFilter(\BiBundle\Entity\ArgumentFilter $argumentFilter)
    {
        $this->argumentFilters[] = $argumentFilter;

        return $this;
    }

    /**
     * Remove argumentFilter
     *
     * @param \BiBundle\Entity\ArgumentFilter $argumentFilter
     */
    public function removeArgumentFilter(\BiBundle\Entity\ArgumentFilter $argumentFilter)
    {
        $this->argumentFilters->removeElement($argumentFilter);
    }

    /**
     * Get argumentFilters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArgumentFilters()
    {
        return $this->argumentFilters;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return Card
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
