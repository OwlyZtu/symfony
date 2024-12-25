<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenreRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, Genre::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'genre' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('genre');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('genre.title LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
