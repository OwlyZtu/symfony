<?php

namespace App\Services;

use App\Entity\Loan;
use App\Repository\LoanRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
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
        'readerId',
        'bookId',
        'loan_date',
        'due_date',
        'return_date'
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
     * @var LoanRepository
     */
    private LoanRepository $loanRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param LoanRepository $loanRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        LoanRepository $loanRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->loanRepository = $loanRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getLoan(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->loanRepository->getAllByFilter($filters, $itemsPerPage, $page);
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