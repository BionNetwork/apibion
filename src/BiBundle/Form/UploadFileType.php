<?php

namespace BiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', FileType::class, [
            'data_class' => 'BiBundle\Entity\File',
            'required' => true,
            'multiple' => true
        ]);
    }
}