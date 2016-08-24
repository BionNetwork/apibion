<?php

namespace BiBundle\Form\Extend\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MemberType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', [
                'class' => 'BiBundle\Entity\User',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'member';
    }
}
