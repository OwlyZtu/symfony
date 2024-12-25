<?php

namespace App\Services;

use App\Entity\Branch;
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
        'description',
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
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
    }

    /**
     * @return array
     */
    public function getBranch(): array
    {
        return $this->entityManager->getRepository(Branch::class)->findAll();
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