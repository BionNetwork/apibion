<?php

namespace BiBundle\Entity\Filter;

class Dashboard extends AbstractFilter
{
    /**
     * Идентификатор рабочего стола
     * @var int
     */
    public $id;

    /**
     * Идентификатор рабочего пользоватея
     * @var int
     */
    public $user_id;

}
