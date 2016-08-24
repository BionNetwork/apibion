<?php
/**
 * @package    BiBundle\Form\Type
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Main user form that is used to save general user fields
 */
class UserMainFields extends Profile
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('login', null, ['description' => 'Логин'])
            ->add('password', PasswordType::class, ['required' => false, 'description' => 'Пароль'])
            ->add('phone', null, ['description' => 'Телефон'])
            ->add('status', 'entity', [
                'class' => 'BiBundle\Entity\UserStatus',
                'choice_label' => 'name',
                'description' => 'Статус пользователя'
            ])
            ->add('role', 'entity', [
                'class' => 'BiBundle\Entity\UserRole',
                'choice_label' => 'name',
                'description' => 'Роль пользователя'
            ]);
    }
}