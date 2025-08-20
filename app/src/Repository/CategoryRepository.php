<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     *
     * @param ManagerRegistry $registry the manager registry for Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Save a Category entity.
     *
     * @param Category $entity the category entity to save
     * @param bool     $flush  whether to flush changes to the database
     */
    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a Category entity.
     *
     * @param Category $entity the category entity to remove
     * @param bool     $flush  whether to flush changes to the database
     */
    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find categories by user.
     *
     * @param \App\Entity\User $user the user
     *
     * @return Category[] the categories
     */
    public function findByUser(\App\Entity\User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find categories by user and type.
     *
     * @param \App\Entity\User $user the user
     * @param string           $type the category type
     *
     * @return Category[] the categories
     */
    public function findByUserAndType(\App\Entity\User $user, string $type): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.owner = :user')
            ->andWhere('c.type = :type')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
