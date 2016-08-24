<?php

namespace BiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', null, [
                'description' => 'Старый пароль'
            ])
            ->add('password', PasswordType::class, [
                    'invalid_message' => 'Пароли не совпадают',
                    'required' => true,
                ]
            );
    }

    public function getName()
    {
        return 'passwordChange';
    }
}
