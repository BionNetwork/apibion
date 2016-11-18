<?php

namespace BiBundle\Entity;

/**
 * CardImage
 */
class CardImage
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Card
     */
    private $card;

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
     * Set priority
     *
     * @param integer $priority
     *
     * @return CardImage
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set file
     *
     * @param File $file
     *
     * @return CardImage
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set card
     *
     * @param Card $card
     *
     * @return CardImage
     */
    public function setCard(Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
