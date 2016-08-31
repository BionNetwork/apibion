<?php

namespace BiBundle\Entity\Filter;

class Activation extends AbstractFilter
{
    /**
     * Идентификатор карточки
     * @var int
     */
    public $card_id;

    /**
     * Идентификатор карточки
     * @var int
     */
    public $user_id;

    /**
     * Идентификатор рабочего стола
     * @var int
     */
    public $dashboard_id;

}
