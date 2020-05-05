<?php

namespace App\Repository;

use App\Entity\TokenContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TokenContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokenContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokenContact[]    findAll()
 * @method TokenContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenContact::class);
    }

    // /**
    //  * @return TokenContact[] Returns an array of TokenContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TokenContact
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
