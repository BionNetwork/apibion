<?php

namespace BiBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('description')
            ->add('description_long')
            ->add('rating')
            ->add('author')
            ->add('image')
            ->add('carousel')
            ->add('type')
            ->add('price')
//            ->add('createdOn', 'datetime')
//            ->add('updatedOn', 'datetime')
//            ->add('cardCategory')
        ;
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
