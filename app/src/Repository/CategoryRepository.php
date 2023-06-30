<?php
/**
 * Category repository.
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
     * Items per page.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * Remove entity.
     *
     * @param Category $entity category entity
     * @param bool     $flush  flush
     */
    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Query all records.
     *
     * @param array $filters filters array
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select('partial category.{id, createdAt, updatedAt, title}')
            ->orderBy('category.createdAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }

    /**
     * Query advertisements by author.
     *
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('category');
    }
}
