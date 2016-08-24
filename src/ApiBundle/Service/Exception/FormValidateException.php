<?php
/**
 * @package    ApiBundle\Service\Exception
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ApiBundle\Service\Normalizer\UnderscoreNormalizer;
use Symfony\Component\Form\FormError;

class FormValidateException extends HttpException
{

    public function __construct(FormInterface $form)
    {
        $errors = $this->getErrors($form);
        parent::__construct(400, 'Validation Failed', null, $errors, 400);
    }

    /**
     * Возвращает массив с ошибками
     *
     * @param FormInterface $form
     * @return array
     */
    private function getErrors(FormInterface $form)
    {
        $errors = $this->processErrors($form);
        $normalizer = $this->getArrayNormalizer();
        $errors = $normalizer->normalize($errors);
        return $errors;
    }

    /**
     * Получает ошибки формы
     *
     * @param FormInterface $form
     * @return mixed
     */
    private function processErrors(FormInterface $form)
    {
        $errors = [];
        $this->processErrorsRecursive($form, $errors);
        $resultArray[$form->getName()] = $errors;
        return $resultArray;
    }

    /**
     * Рекурсивно наполняет массив ошибок
     *
     * @param FormInterface $form
     * @param $errors
     * @return array
     */
    private function processErrorsRecursive(FormInterface $form, &$errors)
    {
        $formErrorIterator = $form->getErrors(true, false);
        foreach ($formErrorIterator as $errorInstance) {
            if ($errorInstance instanceof FormError) {
                $errors['errors'][] = $errorInstance->getMessage();
            } else {
                $childForm = $errorInstance->getForm();
                $this->processErrorsRecursive($childForm, $errors['children'][$childForm->getName()]);
            }
        }
    }

    /**
     * UnderscoreNormalizer
     * @return UnderscoreNormalizer
     */
    protected function getArrayNormalizer()
    {
        return new UnderscoreNormalizer();
    }
}
