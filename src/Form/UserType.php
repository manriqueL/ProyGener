<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', null, [
            "label" => "Nombre"
          ])
          ->add('lastName', null, [
            "label" => "Apellido"
          ])
          ->add('telephone', null, [
            "label" => "Teléfono"
          ])
          ->add('cellphone', null, [
            "label" => "Celular"
          ])
          ->add('email')
          ->add('username')
          ->add('roles', ChoiceType::class, [
             'choices' => User::$rolesUser,
             'expanded' => true,
             'multiple' => true,
             'label' => "Permisos"
          ])
          ->add('password', RepeatedType::class, [
              'type' => PasswordType::class,
              'invalid_message' => 'Los password deben ser iguales.',
              'options' => ['attr' => ['class' => 'password-field']],
              'required' => true,
              'first_options'  => ['label' => 'Contraseña'],
              'second_options' => ['label' => 'Repita Contraseña'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
