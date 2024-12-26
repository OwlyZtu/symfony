<?php

namespace App\Controller;

use App\Services\ReaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 *
 */
#[Route('/Reader', name: 'Reader_routes')]
class ReaderController extends AbstractController
{
    /**
     * @var ReaderService
     */
    private ReaderService $ReaderService;

    /**
     * @param ReaderService $ReaderService
     */
    public function __construct(ReaderService $ReaderService)
    {
        $this->ReaderService = $ReaderService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Readers', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getReaders(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->ReaderService->getReader($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Reader', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getReader(int $id): JsonResponse
    {
        $Reader = $this->ReaderService->getReaderById($id);

        return new JsonResponse($Reader, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Reader', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createReader(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Reader = $this->ReaderService->createReader($requestData);

        return new JsonResponse($Reader, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Reader', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateReader(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Reader = $this->ReaderService->updateReader($id, $requestData);

        return new JsonResponse($Reader, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Reader', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteReader(int $id): JsonResponse
    {
        $this->ReaderService->deleteReader($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}