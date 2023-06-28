<?php
/**
 * Advertisement service.
 */

namespace App\Service;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdvertisementService.
 */
class AdvertisementService implements AdvertisementServiceInterface
{
    /**
     * Advertisement repository.
     */
    private AdvertisementRepository $advertisementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService         Category service interface
     * @param AdvertisementRepository  $advertisementRepository Advertisement repository
     * @param PaginatorInterface       $paginator               Paginator
     */
    public function __construct(CategoryServiceInterface $categoryService, AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator)
    {
        $this->advertisementRepository = $advertisementRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->advertisementRepository->queryByAuthor($filters),
            $page,
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function save(Advertisement $advertisement): void
    {
        if (null === $advertisement->getId()) {
            $advertisement->setCreatedAt(new \DateTimeImmutable());
            $advertisement->setIsActive(false);
        }
        $advertisement->setUpdatedAt(new \DateTimeImmutable());

        $this->advertisementRepository->save($advertisement);
    }

    /**
     * Delete entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void
    {
        $this->advertisementRepository->delete($advertisement);
    }

    /**
     * Find by category.
     *
     * @param int $category Category id
     *
     * @return array Advertisement entity
     */
    public function findAllByCategory(int $category): array
    {
        return $this->advertisementRepository->findAllByCategory($category);
    }

    /**
     * Activate advertisement.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function activate(Advertisement $advertisement): void
    {
        $advertisement->setIsActive(true);
        $advertisement->setUpdatedAt(new \DateTimeImmutable());

        $this->advertisementRepository->save($advertisement);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        return $resultFilters;
    }
}
