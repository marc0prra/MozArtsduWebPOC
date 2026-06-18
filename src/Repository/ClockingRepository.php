<?php

namespace App\Repository;

use App\Entity\Clocking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clocking>
 */
class ClockingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clocking::class);
    }

    /** @return Clocking[] */
    public function findForToday(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.employee', 'e')
            ->addSelect('e')
            ->where('c.createdAt >= :today')
            ->andWhere('c.createdAt < :tomorrow')
            ->setParameter('today', new \DateTimeImmutable('today'))
            ->setParameter('tomorrow', new \DateTimeImmutable('tomorrow'))
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
