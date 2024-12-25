<?php

namespace App\Services;

use App\Entity\Branch;
use App\Repository\BranchRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
use ContainerEiGxgS8\getBranchRepositoryService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class BranchService
{
    /**
     *
     */
    public const REQUIRED_Branch_CREATE_FIELDS = [
        'name',
        'address',
        'phone'
    ];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;
    /**
     * @var BranchRepository
     */
    private BranchRepository $branchRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param BranchRepository $branchRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        BranchRepository $branchRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->branchRepository = $branchRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getBranch(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->branchRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Branch
     */
    public function getBranchById(int $id): Branch
    {
        $Branch = $this->entityManager->getRepository(Branch::class)->find($id);

        if (!$Branch) {
            throw new NotFoundHttpException('Branch not found');
        }

        return $Branch;
    }


    /**
     * @param array $data
     * @return Branch
     * @throws DateMalformedStringException
     */
    public function createBranch(array $data): Branch
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Branch_CREATE_FIELDS);

        $Branch = new Branch();

        return $this->objectHandlerService->saveEntity($Branch, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Branch
     * @throws DateMalformedStringException
     */
    public function updateBranch(int $id, array $data): Branch
    {
        $Branch = $this->getBranchById($id);

        return $this->objectHandlerService->saveEntity($Branch, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteBranch(int $id): void
    {
        $Branch = $this->getBranchById($id);

        $this->entityManager->remove($Branch);
        $this->entityManager->flush();
    }
}