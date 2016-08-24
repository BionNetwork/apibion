<?php

namespace BiBundle\Form\Model\User;

use Symfony\Component\Validator\Constraints as Assert;
use BiBundle\Entity\User;
use BiBundle\Form\Model\BaseModel;

/**
 * Watcher member
 */
class Member extends BaseModel
{

    /**
     * @var User
     *
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}