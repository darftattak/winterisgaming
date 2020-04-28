<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
           
           /*  ->add('shippingAddress', EntityType::class, array(
                "label" => "Adresse d'expédition:",
                'class' => Address::class,
                'choices' => $user->getAddresses(),
            ))
            ->add('billingAddress', EntityType::class, array(
                "label" => "Adresse de Facyuration:",
                'class' => Address::class,
                'choices' => $user->getAddresses(),
            )); */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
