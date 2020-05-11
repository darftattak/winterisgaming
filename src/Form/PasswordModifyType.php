<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;


class PasswordModifyType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('oldPassword', PasswordType::class, array(
                'mapped' => false,
                'constraints' => new UserPassword(array(
                    'message' => 'Mauvais mot de passe'
                    )),
                    'label' => "Ancien mot de passe"
            ))
            ->add('plainPassword',RepeatedType::class, array(
                "type" => PasswordType::class,
                "first_options" => ["label" => "Nouveau mot de passe"],
                "second_options" => ["label" => "Confirmation du mot de passe"],
                "invalid_message" => "Les mots de passe ne correspondent pas",
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
