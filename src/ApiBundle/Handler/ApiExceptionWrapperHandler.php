<?php
/**
 * @package    ApiBundle\Handler
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Handler;

use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;

class ApiExceptionWrapperHandler implements ExceptionWrapperHandlerInterface
{

    public function wrap($data)
    {
        /** @var \Symfony\Component\Debug\Exception\FlattenException $exception */
        $exception = $data['exception'];
        $errors = null;
        if (in_array(
                $exception->getClass(), [
                'ApiBundle\Service\Exception\FormValidateException',
                'ApiBundle\Service\Exception\ParameterValidateException'
            ])) {
            $errors = $exception->getHeaders();
            $exception->setHeaders([]);
        }
        $newException = array(
            'success' => false,
            'exception' => array(
                'code' => $exception->getStatusCode(),
                'message' => $exception->getStatusCode() !== 500 ? $exception->getMessage() : $data['status_text']
            ),
            'errors' => $errors
        );

        return $newException;
    }
}
