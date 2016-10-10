<?php

namespace BiBundle\Form;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextType::class, [
                'required' => false
            ])
            ->add('description_long', TextareaType::class, [
                'required' => false
            ])
            ->add('rating')
            ->add('author')
            ->add('carousel')
            ->add('type')
            ->add('price')
            ->add('cardCategory')
            ->add('cardCategory', EntityType::class, [
                'class' => CardCategory::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('locale', TextType::class, [
                'required' => false,
                'attr' => ['readonly' => true]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\Card'
        ));
    }
}
