<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Nom du produit',
                )
            ))
            ->add('description', null, array(
                'label' => 'Description',
                'attr' => array(
                    'rows' => 4,
                )
            ))
            ->add('pictureFile', FileType::class, array(
                'label' => 'Image',
                'help' => 'Image PNG ou JPEG inférieure à 2M',
            ))
            ->add('price', null, array(
                'label' => 'Prix',
                'widget' => 'single_text',
            ))
            ->add('stock', null, array(
                'label' => 'Quantité',
                'widget' => 'single_text',
            ))
            ->add('state', null, array(
                'label' => 'Etat',
                'help' => "Séléctionnez un état pour votre produit(neuf...)",
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}