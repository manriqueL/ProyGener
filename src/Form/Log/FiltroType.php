<?php

namespace App\Form\Log;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
* Esta clase define los campos que se utilizarán en el filtro
*/
class FiltroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
          ->add("id")
          ->add("action")
          ->add("loggedAt")
          ->add("objectId")
          ->add("objectClass")
          ->add("version")
          ->add("data")
          ->add("username")
          ->setMethod("GET")
      ;
    }

    public function getName()
    {
        return 'filtro';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
          'csrf_protection' => false,
      ));
    }
}
