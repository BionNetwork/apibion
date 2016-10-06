<?php

namespace BiBundle\Form;

use BiBundle\Entity\FilterControlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class ArgumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filterControlType', EntityType::class, [
            'class' => FilterControlType::class,
            'choice_label' => 'name',
            'required' => false,
        ]);
        $builder->add('filtered', CheckboxType::class, [
            'required' => false,
        ]);
    }
}