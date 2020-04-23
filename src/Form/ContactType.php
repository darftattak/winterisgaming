<?php

namespace App\Form;

use App\Model\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('firstname', null, array(
                "label" => "Prénom",
            ))

            ->add('lastname', null, array(
                "label" => "Nom",
            ))

            ->add('email', null, array(
                "label" => "Mail",
            ))

            ->add('topic', ChoiceType::class, array(
                "label" => "Sujet",
                'choices' => [
                    "Lapinou" =>  "Lapinou",
                    "In" => "In",
                    "The" => "The",
                    "Wind" => "Wind", 
                ],
            ))

            ->add('message', TextareaType::class, array(
                "label" => "Votre message",
                "help" => "De 100 à 2500 caractères.",
            ))

            ->add('orderNumber', ChoiceType::class, array(
                "label" => "Commande n°",
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
