<?php

namespace App\Repository;

use App\Entity\PatientsDocteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PatientsDocteurs>
 *
 * @method PatientsDocteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientsDocteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientsDocteurs[]    findAll()
 * @method PatientsDocteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientsDocteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientsDocteurs::class);
    }

    public function add(PatientsDocteurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PatientsDocteurs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PatientsDocteurs[] Returns an array of PatientsDocteurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PatientsDocteurs
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
