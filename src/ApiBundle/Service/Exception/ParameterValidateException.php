<?php

namespace ApiBundle\Service\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ParameterValidateException extends HttpException
{

    public function __construct($entityName, $parameterName, $message)
    {
        $errors[$entityName]['errors']=null;
        $errors[$entityName]['children'][$parameterName]['errors'][] = $message;
        $errors[$entityName]['children'][$parameterName]['children'][] = null;
        parent::__construct(400, 'Validation Failed', null, $errors, 400);
    }
}
