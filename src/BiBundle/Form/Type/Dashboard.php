<?php
/**
 * @package    BiBundle\Form\Type
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Dashboard extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['description' => 'Название']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\Dashboard'
        ));
    }

    public function getName()
    {
        return 'dashboard';
    }
}