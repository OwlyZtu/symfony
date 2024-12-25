<?php

namespace App\Controller;

use App\Services\LibrarianService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Librarian', name: 'Librarian_routes')]
class LibrarianController extends AbstractController
{
    /**
     * @var LibrarianService
     */
    private LibrarianService $LibrarianService;

    /**
     * @param LibrarianService $LibrarianService
     */
    public function __construct(LibrarianService $LibrarianService)
    {
        $this->LibrarianService = $LibrarianService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Librarians', methods: ['GET'])]
    public function getLibrarians(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->LibrarianService->getLibrarian($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Librarian', methods: ['GET'])]
    public function getLibrarian(int $id): JsonResponse
    {
        $Librarian = $this->LibrarianService->getLibrarianById($id);

        return new JsonResponse($Librarian, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Librarian', methods: ['POST'])]
    public function createLibrarian(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Librarian = $this->LibrarianService->createLibrarian($requestData);

        return new JsonResponse($Librarian, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Librarian', methods: ['PATCH'])]
    public function updateLibrarian(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Librarian = $this->LibrarianService->updateLibrarian($id, $requestData);

        return new JsonResponse($Librarian, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Librarian', methods: ['DELETE'])]
    public function deleteLibrarian(int $id): JsonResponse
    {
        $this->LibrarianService->deleteLibrarian($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}