<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Enum\MainType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findRecentTransactions(int $limit): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findCurrentMonthTransactionsByType(MainType $transaction): array
    {
        $start = (new \DateTime('first day of this month'))->setTime(0, 0, 0);

        $end = (new \DateTime('last day of this month'))->setTime(23, 59, 59);

        return $this->createQueryBuilder('t')
            ->andWhere('t.mainType = :type')
            ->setParameter('type', $transaction)
            ->andWhere('t.date BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    public function findGivenMonthTransactions(string $year, string $month): array
    {
        $start = new \DateTime("$year-$month-01 00:00:00");
        $end = (clone $start)->modify('last day of this month 23:59:59');

        return $this->createQueryBuilder('t')
            ->andWhere('t.date BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findTransactionYearAndMonthList(): array
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT (t.date) as date')
            ->orderBy('t.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
