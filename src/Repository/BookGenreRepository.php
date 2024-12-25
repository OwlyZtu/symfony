<?php

namespace App\Repository;

use App\Entity\BookGenre;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<BookGenre>
 */
class BookGenreRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, BookGenre::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'bookGenre' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('bookGenre');

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
