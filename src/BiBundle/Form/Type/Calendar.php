<?php

namespace BiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Calendar extends AbstractType
{
    const DAY = 0;
    const MONTH = 1;
    const YEAR = 2;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = is_string($options['format']) ? $options['format'] : null;
        $builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
            null, null, -1, null, \IntlDateFormatter::GREGORIAN, $pattern
        ));
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        if (!isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] = '';
        }
        $view->vars['attr']['class'] .= " " . $this->getClass($options['type']);
        parent::buildView($view, $form, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'format' => 'dd.MM.yyyy',
            'compound' => false,
            'type' => self::DAY,
        ));
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'calendar';
    }

    /**
     * Возвращает правильный класс для календаря по типу календаря
     *
     * @param int $typeCalendar тип календаря
     *
     * @return string
     */
    private function getClass($typeCalendar)
    {
        $currentClass = "form-control input-inline ";
        switch ($typeCalendar) {
            case self::MONTH :
                $currentClass .= 'datepicker-month';
                break;
            case self::YEAR :
                $currentClass .= 'datepicker-year';
                break;
            case self::DAY :
            default:
                $currentClass .= 'datepicker-day';
                break;
        }
        return $currentClass;
    }

}
