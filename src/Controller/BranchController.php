<?php

namespace App\Controller;

use App\Services\BranchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Branch', name: 'Branch_routes')]
class BranchController extends AbstractController
{
    /**
     * @var BranchService
     */
    private BranchService $BranchService;

    /**
     * @param BranchService $BranchService
     */
    public function __construct(BranchService $BranchService)
    {
        $this->BranchService = $BranchService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Branches', methods: ['GET'])]
    public function getBranches(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->BranchService->getBranch($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Branch', methods: ['GET'])]
    public function getBranch(int $id): JsonResponse
    {
        $Branch = $this->BranchService->getBranchById($id);

        return new JsonResponse($Branch, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Branch', methods: ['POST'])]
    public function createBranch(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Branch = $this->BranchService->createBranch($requestData);

        return new JsonResponse($Branch, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Branch', methods: ['PATCH'])]
    public function updateBranch(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Branch = $this->BranchService->updateBranch($id, $requestData);

        return new JsonResponse($Branch, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Branch', methods: ['DELETE'])]
    public function deleteBranch(int $id): JsonResponse
    {
        $this->BranchService->deleteBranch($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}