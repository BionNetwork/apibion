<?php

namespace BiBundle\Form;

use BiBundle\Entity\Card;
use BiBundle\Entity\FilterControlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArgumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('code', TextType::class, ['required' => true]);
        $builder->add('datatype', TextType::class, ['required' => false]);
        $builder->add('dimension', TextType::class, ['required' => false]);
        $builder->add('description', TextareaType::class, ['required' => false]);
        $builder->add('card', EntityType::class, [
            'class' => Card::class,
            'choice_label' => 'name',
            'required' => false,
        ]);
        $builder->add('locale', TextType::class, [
            'required' => false,
            'attr' => ['readonly' => true]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BiBundle\Entity\Argument',
        ]);
    }
}