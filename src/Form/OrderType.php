<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;


class OrderType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser(); 
        $builder
            ->add ('paymentToken', HiddenType::class)
            ->add('shippingAddress', EntityType::class, array(
                "label" => "Adresse d'expédition:",
                'class' => Address::class,
                'choices' => $user->getAddresses(),
            ))
            ->add('billingAddress', EntityType::class, array(
                "label" => "Adresse de Facturation:",
                'class' => Address::class,
                'choices' => $user->getAddresses(),
            ))
            ->add('cgv', CheckboxType::class, array(
                "label" => "J'accepte les Conditions générales de vente de Winter Is Gaming",
                'mapped' => false,
                'required' => true
            )) 
            ->add('artSeven', CheckboxType::class, array(
                "label" => "J'accepte plus particulièrement l'article 7 des Conditions générales de vente de Winter Is Gaming",
                'mapped' => false,
                'required' => true
            ))
            ->add('loyalty', CheckboxType::class, array(
                "label" => "Utiliser mes points de fidélité",
                'mapped' => false,
                'required' => false,
            )); 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
