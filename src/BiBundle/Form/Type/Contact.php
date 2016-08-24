<?php

namespace BiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class Contact extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phoneValidator = new Regex('/[0-9]{11}$/');
        $phoneValidator->message = 'This value should be like 7XXXXXXXXXX';
        $builder->add('value', TextType::class, [
            'constraints' => [
                new NotBlank(),
                $phoneValidator,
            ],
        ]);
        $builder->add('type', HiddenType::class, [
            'data' => \BiBundle\Entity\UserContact::TYPE_PHONE,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\UserContact',
        ));
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'contact';
    }
}
