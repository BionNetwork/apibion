<?php

namespace BiBundle\Entity\Filter;

class Card extends AbstractFilter
{
    /**
     * Идентификатор карточки
     * @var int
     */
    public $id;

    /**
     * Идентификатор пользователя
     * @var int
     */
    public $user_id;

}
