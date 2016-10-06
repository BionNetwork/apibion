<?php
/**
 * @package    BiBundle\Form
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('card', EntityType::class, [
                'class' => 'BiBundle\Entity\Card',
                'choice_label' => 'name',
                'description' => 'Карточка',
                'required' => true,
                'empty_value' => 'Не выбрана'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\Purchase',
        ));
    }

    public function getBlockPrefix()
    {
        return 'purchase';
    }
}