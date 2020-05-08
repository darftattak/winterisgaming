<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ModifyType extends AbstractType
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
            ->add('username', null, array(
                "label" => "Pseudo",
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
