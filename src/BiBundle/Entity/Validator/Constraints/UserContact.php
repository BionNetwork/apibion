<?php

namespace BiBundle\Entity\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserContact extends Constraint
{
    public $message = 'The string "%string%" contains an illegal character: the phone number must be like 72222222222.';
}
