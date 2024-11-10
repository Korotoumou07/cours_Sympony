<?php

namespace App\Repository;

use App\Entity\Dette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Dette>
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

       /**
        * @return Paginator Returns an array of Dette objects
        */
       public function findByClient($idClient,int $page=1,int $limit=3): Paginator
     {
         $query=$this->createQueryBuilder('d')

             ->andWhere('d.client = :val')
              ->setParameter('val', $idClient)
           ->orderBy('d.id', 'ASC')
           ->setFirstResult(($page-1)*$limit)
           ->setMaxResults($limit)
               ->getQuery();

         $paginator = new Paginator($query);
         return $paginator;
      }

      /**
        * @return Paginator Returns an array of Dette objects
        */
        public function findAllDettes(int $page=1,int $limit=3): Paginator
        {
            $query=$this->createQueryBuilder('d')
   
              ->orderBy('d.id', 'ASC')
              ->setFirstResult(($page-1)*$limit)
              ->setMaxResults($limit)
                  ->getQuery();
   
            $paginator = new Paginator($query);
            return $paginator;
         }
   

    //    public function findOneBySomeField($value): ?Dette
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}