<?php

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOffer>
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

    public function findLatestActiveOffers(int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('j')
            ->select('NEW App\DTO\JobOfferHomeDTO(
                j.jobTitle,
                j.salary,
                j.closingAt,
                j.jobLocation,
                j.reference,
                j.description,
                j.slug,
                c.name
            )')
            ->leftJoin('j.category', 'c')
            ->where('j.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('j.createdAt', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return JobOffer[] Returns an array of JobOffer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JobOffer
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
