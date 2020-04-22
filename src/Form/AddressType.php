<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                "label" => "Adresse",
                "help" => "Numéro, voirie, et éventuelles informations nécessaire à la livraison.",
            ))
            ->add('zipcode', TextType::class, array(
                "label" => "Code Postal",
            ))
            ->add('city', null, array(
                "label" => "Ville",
            ))
            ->add('country', null, array(
                "label" => "Pays",
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
