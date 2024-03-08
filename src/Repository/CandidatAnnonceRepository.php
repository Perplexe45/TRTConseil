<?php

namespace App\Repository;

use App\Entity\CandidatAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidatAnnonce>
 *
 * @method CandidatAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidatAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidatAnnonce[]    findAll()
 * @method CandidatAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidatAnnonce::class);
    }

//    /**
//     * @return CandidatAnnonce[] Returns an array of CandidatAnnonce objects
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

//    public function findOneBySomeField($value): ?CandidatAnnonce
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
