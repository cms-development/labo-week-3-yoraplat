<?php

namespace App\Repository;

use App\Entity\Kamp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Kamp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kamp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kamp[]    findAll()
 * @method Kamp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KampRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kamp::class);
    }

    // /**
    //  * @return Kamp[] Returns an array of Kamp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kamp
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
