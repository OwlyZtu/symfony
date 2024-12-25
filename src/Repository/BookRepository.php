<?php

namespace App\Repository;

use App\Entity\Book;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, Book::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'books' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('book');

        if (!empty($data['title'])) {
            $queryBuilder->andWhere('book.title LIKE :title')
                ->setParameter('title', '%' . $data['title'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
