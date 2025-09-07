<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    /**
     * TransactionRepository constructor.
     *
     * @param ManagerRegistry $registry the manager registry for Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * Save a Transaction entity.
     *
     * @param Transaction $entity the transaction entity to save
     * @param bool        $flush  whether to flush changes to the database
     */
    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a Transaction entity.
     *
     * @param Transaction $entity the transaction entity to remove
     * @param bool        $flush  whether to flush changes to the database
     */
    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find transactions by user with optimized query (1-NF denormalized).
     *
     * @param User   $user    the user entity
     * @param array  $filters optional filters
     * @param int    $limit   optional limit
     * @param int    $offset  optional offset
     *
     * @return Transaction[] the transactions
     */
    public function findByUserOptimized(User $user, array $filters = [], int $limit = null, int $offset = null): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t', 'p', 'c', 'tags')
            ->leftJoin('t.portfolio', 'p')
            ->leftJoin('t.category', 'c')
            ->leftJoin('t.tags', 'tags')
            ->where('p.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('t.date', 'DESC');

        // Apply filters
        if (!empty($filters['tag'])) {
            $qb->andWhere('tags.id = :tagId')
                ->setParameter('tagId', $filters['tag']);
        }

        if (!empty($filters['category'])) {
            $qb->andWhere('c.name = :categoryName')
                ->setParameter('categoryName', $filters['category']);
        }

        if (!empty($filters['portfolio'])) {
            $qb->andWhere('p.name = :portfolioName')
                ->setParameter('portfolioName', $filters['portfolio']);
        }

        if (!empty($filters['start_date'])) {
            $qb->andWhere('t.date >= :startDate')
                ->setParameter('startDate', new \DateTime($filters['start_date']));
        }

        if (!empty($filters['end_date'])) {
            $qb->andWhere('t.date <= :endDate')
                ->setParameter('endDate', new \DateTime($filters['end_date'] . ' 23:59:59'));
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get user categories for dropdown (denormalized).
     *
     * @param User $user the user entity
     *
     * @return array the category names
     */
    public function getUserCategories(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT c.name')
            ->leftJoin('t.portfolio', 'p')
            ->leftJoin('t.category', 'c')
            ->where('p.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Get user portfolios for dropdown (denormalized).
     *
     * @param User $user the user entity
     *
     * @return array the portfolio names
     */
    public function getUserPortfolios(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT p.name')
            ->leftJoin('t.portfolio', 'p')
            ->where('p.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Get transaction statistics for user (denormalized).
     *
     * @param User $user the user entity
     *
     * @return array the statistics
     */
    public function getUserStatistics(User $user): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('
                COUNT(t.id) as total_transactions,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) as total_income,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) as total_expenses,
                AVG(t.amount) as average_amount,
                MIN(t.date) as first_transaction_date,
                MAX(t.date) as last_transaction_date
            ')
            ->leftJoin('t.portfolio', 'p')
            ->where('p.owner = :user')
            ->setParameter('user', $user);

        $result = $qb->getQuery()->getSingleResult();

        return [
            'total_transactions' => (int) $result['total_transactions'],
            'total_income' => (float) $result['total_income'],
            'total_expenses' => (float) $result['total_expenses'],
            'average_amount' => (float) $result['average_amount'],
            'first_transaction_date' => $result['first_transaction_date'],
            'last_transaction_date' => $result['last_transaction_date'],
        ];
    }

    /**
     * Get monthly transaction summary (denormalized).
     *
     * @param User $user the user entity
     * @param int  $year the year
     *
     * @return array the monthly summary
     */
    public function getMonthlySummary(User $user, int $year): array
    {
        return $this->createQueryBuilder('t')
            ->select('
                MONTH(t.date) as month,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) as income,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) as expenses,
                COUNT(t.id) as transaction_count
            ')
            ->leftJoin('t.portfolio', 'p')
            ->where('p.owner = :user')
            ->andWhere('YEAR(t.date) = :year')
            ->setParameter('user', $user)
            ->setParameter('year', $year)
            ->groupBy('MONTH(t.date)')
            ->orderBy('MONTH(t.date)', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
