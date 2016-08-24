<?php

namespace BiBundle\Entity\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UserContactValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        /* @var $userContactObject \BiBundle\Entity\UserContact */
        $userContactObject = $this->context->getRoot();
        $mask = null;
        switch ($userContactObject->getType()) {
            case \BiBundle\Entity\UserContact::TYPE_PHONE:
                $mask = '/^[0-9]{11}$/';
                $mesage = 'Value must have valid phone number';
                break;
            case \BiBundle\Entity\UserContact::TYPE_EMAIL:
                $mask = '/^.+\@\S+\.\S+$/';
                $mesage = 'Value must have valid email address';
                break;
        }
        if ($mask && !preg_match($mask, $value, $matches)) {
            $this->context->buildViolation($mesage)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
