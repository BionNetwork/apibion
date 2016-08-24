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

class Profile extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, ['description' => 'Имя'])
            ->add('lastname', null, ['description' => 'Фамилия'])
            ->add('middlename', null, ['description' => 'Отчество'])
            ->add('email', null, ['description' => 'Email'])
            ->add('birthDate', Calendar::class, ['type' => Calendar::DAY, 'description' => 'Дата рождения'])
            ->add('position', null, ['description' => 'Должность'])
            ->add('NonDefaultContacts', CollectionType::class, [
                'entry_type' => Contact::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [],
                'by_reference' => false,
                'required' => false,
                'description' => 'Список дополнительных телефонов пользователя'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BiBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}