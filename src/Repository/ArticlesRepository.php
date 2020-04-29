<?php

namespace Repository;

use

class ArticlesRepository extends ServiceEntityRepository
{
    public function__construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articles::class);
    }
} 