<?php

namespace BiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BiBundle\Entity\UserRole;

class UserRoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'choices' => [
                    UserRole::ROLE_USER => 'Пользователь',
                    UserRole::ROLE_ADMIN => 'Администратор'
                ],
                'label' => 'Role'
            ])
            ->add('title');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\UserRole'
        ));
    }
}
