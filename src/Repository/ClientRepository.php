<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }
    

    
    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /**
     * Récupère tous les clients ordonnés par le champ `surname`
     * 
     * @return Client[] Returns an array of Client objects
     */
    public function findAllClients(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.surname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les clients selon le `surname` et le `telephone`
     *
     * @param string|null $surname
     * @param string|null $telephone
     * @return Client[]
     */
    public function findBySurnameAndTelephone(?string $surname, ?string $telephone): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($surname) {
            $qb->andWhere('c.surname = :surname')
               ->setParameter('surname', $surname);
        }

        if ($telephone) {
            $qb->andWhere('c.telephone = :telephone')
               ->setParameter('telephone', $telephone);
        }

        return $qb->getQuery()->getResult();
    }
}
