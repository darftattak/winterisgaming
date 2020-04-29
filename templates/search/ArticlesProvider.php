<?php 

namespace App\Form;

use Form\Provider\Base;

class ArticlesProvider extends Base 
{
    const PLATEFORME = [
        'PC',
        'Playstation',
        'Xbox',
        'Nintendo',
        'Mobile',
    ];
    const TYPE = [
        'Jeux Vidéo',
        'Accessoires',
        'Bundles',
    ];

    public function articlesPlateforme()
    {
        return self::randomElement(self::PLATEFORME);
    }

    public function articlesType()
    {
        return self::randomElement(self::TYPE);
    }
    
    public function articlesPrice()
    {
        return rand(0, 25, 50, 100, 200);
    }