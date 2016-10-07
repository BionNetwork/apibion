<?php

namespace BiBundle\Form;

use BiBundle\Entity\FilterControlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArgumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('code');
        $builder->add('datatype', TextType::class, ['required' => false]);
        $builder->add('dimension', TextType::class, ['required' => false]);
        $builder->add('description', TextareaType::class);
        $builder->add('filterControlType', EntityType::class, [
            'class' => FilterControlType::class,
            'choice_label' => 'name',
            'required' => false,
        ]);
    }
}