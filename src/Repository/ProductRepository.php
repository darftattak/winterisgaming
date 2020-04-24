<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

<<<<<<< HEAD

    public function searchByName( $query ) {
        $stmt = $this->createQueryBuilder('e');
        $stmt->where( 'e.name LIKE :query' );
        $stmt->setParameter('query', '%' . $query . '%');

        return $stmt->getQuery()->getResult();
    }

    public function getRandom() {
        $stmt = $this->createQueryBuilder('e');
        $stmt->select('e.id');
        //TODO
        // Installer https://github.com/beberlei/DoctrineExtensions
        // Ajouter une séléction aléatoire
        $stmt->orderBy('RAND()');
        $stmt->setMaxResults(1);

        return $stmt->getQuery()->getSingleScalarResult();
    }

=======
>>>>>>> d75ca8bc80921d0ef7745426dd6ff7af713c3c31
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
