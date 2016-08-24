<?php

namespace BiBundle\Form\Extend\Issue;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BiBundle\Form\Type\Calendar;

class EditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startDate', Calendar::class, [
                'type' => Calendar::DAY,
            ])
            ->add('dueDate', Calendar::class, [
                'type' => Calendar::DAY,
            ])
            ->add('project', 'entity', [
                'class' => 'BiBundle\Entity\Project',
            ])
            ->add('priority', 'entity', [
                'class' => 'BiBundle\Entity\IssuePriority',
            ])
            ->add('assignedTo', 'entity', [
                'class' => 'BiBundle\Entity\User',
            ])
            ->add('parent', 'entity', [
                'class' => 'BiBundle\Entity\Issue',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'issue';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\Issue'
        ));
    }
}
