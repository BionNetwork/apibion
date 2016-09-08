<?php

namespace BiBundle\Entity\Filter;

class Activation extends AbstractFilter
{
    /**
     * Идентификатор активации
     * @var int
     */
    public $id;

    /**
     * Идентификатор карточки
     * @var int
     */
    public $card_id;

    /**
     * Идентификатор пользователя
     * @var int
     */
    public $user_id;

    /**
     * Код статуса
     * @var string
     */
    public $activation_status;

    /**
     * Идентификатор рабочего стола
     * @var int
     */
    public $dashboard_id;

}
