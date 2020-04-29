<?php

use App\Form\ArticlesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchArticlesType extends AbstractType
{
    const PRICE = [0, 25, 50, 100, 200];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', ChoiceType::class, [
                'choices' => [
                    array_combine(ArticlesProvider::TYPE, ArticlesProvider::TYPE)
                ]
            ])
            ->add('plateforme', ChoiceType::class, [
                'choices' => [
                    array_combine(ArticlesProvider::PLATEFORME, ArticlesProvider::PLATEFORME)
                ]
            ])
            ->add ('minimumPrice', ChoiceType::class, [
                'label' => 'Prix minimum',
                'choices' => array_combine(self::PRICE, self::PRICE)
            ->add ('maximumPrice', ChoiceType::class, [
                'label' => 'Prix maximum',
                'choices' => array_combine(self::PRICE, self::PRICE)
            ])
            ->add('recherche', SubmitType::class)
    