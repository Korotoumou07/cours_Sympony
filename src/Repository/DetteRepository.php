<?php

namespace App\Repository;

use App\Entity\Dette;
use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Dette>
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    //    /**
    //     * @return Dette[] Returns an array of Dette objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Dette
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère toutes les dettes d'un client donné
     *
     * @param Client $client
     * @return Dette[]
     */
    // public function findByClient(Client $client): array
    // {
    //     return $this->createQueryBuilder('d')
    //         ->andWhere('d.client = :client')
    //         ->setParameter('client', $client)
    //         ->orderBy('d.createAt', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }
    public function findByClientId($clientId)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.client = :clientId')
            ->setParameter('clientId', $clientId)
            // ->orderBy('d.date', 'ASC') // Tri par date ou autre critère
            ->getQuery()
            ->getResult();
    }
}
