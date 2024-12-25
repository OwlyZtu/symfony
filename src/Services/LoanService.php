<?php

namespace App\Services;

use App\Entity\Loan;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class LoanService
{
    /**
     *
     */
    public const REQUIRED_Loan_CREATE_FIELDS = [
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
    public function getLoan(): array
    {
        return $this->entityManager->getRepository(Loan::class)->findAll();
    }

    /**
     * @param int $id
     * @return Loan
     */
    public function getLoanById(int $id): Loan
    {
        $Loan = $this->entityManager->getRepository(Loan::class)->find($id);

        if (!$Loan) {
            throw new NotFoundHttpException('Loan not found');
        }

        return $Loan;
    }


    /**
     * @param array $data
     * @return Loan
     * @throws DateMalformedStringException
     */
    public function createLoan(array $data): Loan
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Loan_CREATE_FIELDS);

        $Loan = new Loan();

        return $this->objectHandlerService->saveEntity($Loan, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Loan
     * @throws DateMalformedStringException
     */
    public function updateLoan(int $id, array $data): Loan
    {
        $Loan = $this->getLoanById($id);

        return $this->objectHandlerService->saveEntity($Loan, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteLoan(int $id): void
    {
        $Loan = $this->getLoanById($id);

        $this->entityManager->remove($Loan);
        $this->entityManager->flush();
    }
}