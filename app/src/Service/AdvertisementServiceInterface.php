<?php
/**
 * Advertisement service interface.
 */

namespace App\Service;

use App\Entity\Advertisement;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AdvertisementServiceInterface.
 */
interface AdvertisementServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function save(Advertisement $advertisement): void;

    /**
     * Delete entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void;

    /**
     * Find by title.
     *
     * @param int $category Category id
     *
     * @return array|null array
     */
    public function findAllByCategory(int $category): array;
}
