<?php

namespace App\Repository;

use App\Entity\Branch;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Branch>
 */
class BranchRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, Branch::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'branch' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('branch');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('branch.title LIKE :name')
                ->setParameter('name', '%' . $data['name'] . '%');
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
