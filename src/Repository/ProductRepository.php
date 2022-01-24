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
    public function findByOnline()
    {
        //j'appelle doctrine pour me permettre de faire ma requète
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT * 
            FROM App\Entity\Product 
            WHERE online = 1'
            
        );
        return $query->getResult();
        
        
    }

     /**
     * Récupère les produits avec la mention higlighted
     * en DQL
     */
    public function findByHighlighted()
    {
        //j'appelle doctrine pour me permettre de faire ma requète
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT * 
            FROM App\Entity\Product 
            WHERE hihlighted = 1'
            
        );
        return $query->getResult();
        
        
    }
    public function findByCategoryId($id)
    {
        //j'appelle doctrine pour me permettre de faire ma requète
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT * 
            FROM App\Entity\Product 
            WHERE category_id = :id'
            
        )->setParameter('id', $id);
        return $query->getResult();
        
        
    }

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
