<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Model\Contact;
use Doctrine\ORM\EntityRepository;
use App\Repository\OrderRepository;
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
                            "Lapinou" =>  "Lapinou",
                            "In" => "In",
                            "The" => "The",
                            "Commande" => "Commande",
                            "Wind" => "Wind",
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
                        "Lapinou" =>  "Lapinou",
                        "In" => "In",
                        "The" => "The",
                        "Wind" => "Wind",
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
