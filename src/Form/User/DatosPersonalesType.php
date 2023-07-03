<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatosPersonalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', null, [
            'label' => "Nombre"
          ])
          ->add('lastName', null, [
            'label' => "Apellido"
          ])
          ->add('telephone', null, [
            'label' => "Teléfono"
          ])
          ->add('cellphone', null, [
            'label' => "Celular"
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
