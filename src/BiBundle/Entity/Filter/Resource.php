<?php

namespace BiBundle\Entity\Filter;

class Resource extends AbstractFilter
{
    /**
     * Идентификатор источника
     * @var int
     */
    public $id;

    /**
     * Идентификатор активации
     * @var int
     */
    public $activation_id;

    /**
     * Идентификатор пользователя
     * @var int
     */
    public $user_id;

}
