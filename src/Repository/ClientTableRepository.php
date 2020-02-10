<?php

namespace App\Repository;

use App\Entity\ClientTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ClientTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientTable[]    findAll()
 * @method ClientTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientTable::class);
    }

    // /**
    //  * @return ClientTable[] Returns an array of ClientTable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientTable
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
