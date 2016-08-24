<?php

namespace BiBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use BiBundle\Form\Type\UserMainFields;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * All user fields live here
 */
class UserType extends UserMainFields
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('isActive', null, ['description' => 'Активен'])
            ->add('isSuperuser', null, ['description' => 'Суперпользователь'])
            ->add('avatar', FileType::class, ['required' => false, 'data_class' => null, 'description' => 'Фото']);
    }
}
