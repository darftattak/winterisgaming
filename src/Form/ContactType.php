<?php

namespace App\Form;

use App\Entity\Order;
use App\Model\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options )
    {
        $user = $this->security->getUser();
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
            ->add('message', TextareaType::class, array(
                "label" => "Votre message",
                "help" => "De 100 à 2500 caractères.",
            ))
        ;
            
            if ($this->security->getUser()) {
                $builder
                    ->add('topic', ChoiceType::class, array(
                        "label" => "Sujet",
                        'choices' => [
                            "Retours et remplacements" =>  "Retours et remplacments",
                            "Accès, modification, suppression des données personnelles" => "Accès, modification, suppression des données personnelles",
                            "Tarifications" => "Tarifications",
                            "Signaler un problème" => "Signaler un problème",
                            "Commandes" => "Commandes",
                            "Autres demandes" => "Autres demandes",
                        ],
                    ))
                    ->add('orderNumber', EntityType::class, array(
                        "label" => "Commande n°",
                        'class' => Order::class,
                        'choices' => $user->getOrders(),
                    ));
            } else {

                $builder

                ->add('topic', ChoiceType::class, array(
                    "label" => "Sujet",
                    'choices' => [
                        "Retours et remplacments" =>  "Retours et remplacments",
                        "Accès, modification, suppression des données personnelles" => "Accès, modification, suppression des données personnelles",
                        "Tarifications" => "Tarifications",
                        "Tarifications" => "Tarifications",
                        "Signaler un problème" => "Signaler un problème",
                        "Autres demandes" => "Autres demandes",
                    ],
                ));

            };

            
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
