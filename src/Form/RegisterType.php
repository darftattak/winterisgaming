<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('plainPassword', RepeatedType::class, array(
                "type" => PasswordType::class,
                "first_options" => ["label" => "Mot de passe"],
                "second_options" => ["label" => "Confirmation du mot de passe"],
                "invalid_message" => "Les mots de passe ne correspondent pas",
            ))
            ->add('avatarFile', FileType::class, array(
                "label" => "Avatar",
                "help" => "Image PNG ou JPEG inférieure à 2M"
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
