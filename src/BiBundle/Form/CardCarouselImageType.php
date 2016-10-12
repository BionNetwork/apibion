<?php

namespace BiBundle\Form;

use BiBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class CardCarouselImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var File[] $files */
        $files = $options['data'];
        $choices = [];
        foreach ($files as $file) {
            $choices[$file->getId()] = $file->getPath();
        }

        $builder->add('uploadedImages', FileType::class, [
            'data_class' => File::class,
            'required' => false,
            'multiple' => true,
            'label' => 'Upload'
        ]);
        $builder->add('deletedImages', ChoiceType::class, [
            'choices' => $choices,
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'label' => 'Delete'
        ]);
    }
}