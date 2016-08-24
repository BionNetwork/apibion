<?php

namespace BiBundle\Form\Extend\Issue;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CreateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('parent', 'entity', [
                'class' => 'BiBundle\Entity\Issue',
            ])
            ->add('project');
    }

    public function getBlockPrefix()
    {
        return 'issue';
    }
}
