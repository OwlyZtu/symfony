<?php

namespace App\Repository;

use App\Entity\BookPublisher;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<BookPublisher>
 */
class BookPublisherRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, BookPublisher::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'bookPublisher' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('bookPublisher');

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
