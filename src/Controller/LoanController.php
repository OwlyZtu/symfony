<?php

namespace App\Controller;

use App\Services\LoanService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Loan', name: 'Loan_routes')]
class LoanController extends AbstractController
{
    /**
     * @var LoanService
     */
    private LoanService $LoanService;

    /**
     * @param LoanService $LoanService
     */
    public function __construct(LoanService $LoanService)
    {
        $this->LoanService = $LoanService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Loans', methods: ['GET'])]
    public function getLoans(): JsonResponse
    {
        $Loans = $this->LoanService->getLoan();

        return new JsonResponse($Loans, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Loan', methods: ['GET'])]
    public function getLoan(int $id): JsonResponse
    {
        $Loan = $this->LoanService->getLoanById($id);

        return new JsonResponse($Loan, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Loan', methods: ['POST'])]
    public function createLoan(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Loan = $this->LoanService->createLoan($requestData);

        return new JsonResponse($Loan, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Loan', methods: ['PATCH'])]
    public function updateLoan(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Loan = $this->LoanService->updateLoan($id, $requestData);

        return new JsonResponse($Loan, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Loan', methods: ['DELETE'])]
    public function deleteLoan(int $id): JsonResponse
    {
        $this->LoanService->deleteLoan($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}