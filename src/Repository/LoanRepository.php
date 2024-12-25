<?php

namespace App\Repository;

use App\Entity\Loan;
use App\Services\Utils\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    public function __construct(ManagerRegistry $registry, PaginationService $paginationService)
    {
        parent::__construct($registry, Loan::class);
        $this->paginationService = $paginationService;
    }

    #[ArrayShape([
        'loan' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('loan');

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
