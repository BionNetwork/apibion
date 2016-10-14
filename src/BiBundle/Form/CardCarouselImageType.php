<?php

namespace BiBundle\Form;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCarouselImage;
use BiBundle\Entity\File;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardCarouselImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('priority', TextType::class, [
                'attr' => ['style' => 'width: 35px']
            ])
            ->add('file', EntityType::class, [
                'class' => File::class,
                'choice_label' => 'path',
                'required' => false,
                'disabled' => true,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    return $repository->createQueryBuilder('f')
                        ->leftJoin('f.cardCarouselImage', 'ci')
                        ->where('ci.card = :card')
                        ->orderBy('ci.priority', 'asc')
                        ->setParameter('card', $options['card']);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('card');
        $resolver->setDefaults([
            'data_class' => CardCarouselImage::class,
            'card' => new Card()
        ]);
    }
}