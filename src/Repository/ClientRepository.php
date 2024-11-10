<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

        /**
         * @return Client[] Returns an array of Client objects
        */
   public function findByClientWithOrUser(string $statut): array
      {
        $value=$statut=="Oui"?"not null":"null";
        //select * from client c
         $query= $this->createQueryBuilder('c');
        //select * from client where c.user =:val
             $query->where('c.user is '.$value);
        //select * from client where c.user null
             //$query->setParameter('val',null);
               //->setMaxResults(10)
        //generer et executer  la requete sql
              return $query->getQuery()->getResult(); // permet de recuperer le resultat de la requete sous forme de liste
          
        }
        /**
        * @return Paginator Returns an array of Dette objects
        */
        public function findAllClients(int $page=1,int $limit=3): Paginator
        {
            $query=$this->createQueryBuilder('c')
   
              ->orderBy('c.id', 'ASC')
              ->setFirstResult(($page-1)*$limit)
              ->setMaxResults($limit)
                  ->getQuery();
   
            $paginator = new Paginator($query);
            return $paginator;
         }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}