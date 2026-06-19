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

    /**
     * Retourne tous les pointages du jour courant, triés du plus récent au plus ancien.
     * Charge les salariés associés en une seule requête (JOIN) pour éviter les requêtes N+1.
     *
     * @return Clocking[]
     */
    public function findForToday(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.employee', 'e')
            ->addSelect('e') // chargement eager pour éviter les requêtes supplémentaires
            ->where('c.createdAt >= :today')
            ->andWhere('c.createdAt < :tomorrow')
            ->setParameter('today', new \DateTimeImmutable('today'))
            ->setParameter('tomorrow', new \DateTimeImmutable('tomorrow'))
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
