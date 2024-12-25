<?php

namespace App\Repository;

use App\Entity\Reader;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Reader>
 */
class ReaderRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, Reader::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'reader' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('reader');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('reader.title LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
