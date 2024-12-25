<?php

namespace App\Controller;

use App\Services\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Author', name: 'Author_routes')]
class AuthorController extends AbstractController
{
    /**
     * @var AuthorService
     */
    private AuthorService $AuthorService;

    /**
     * @param AuthorService $AuthorService
     */
    public function __construct(AuthorService $AuthorService)
    {
        $this->AuthorService = $AuthorService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Authors', methods: ['GET'])]
    public function getAuthors(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->AuthorService->getAuthor($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Author', methods: ['GET'])]
    public function getAuthor(int $id): JsonResponse
    {
        $Author = $this->AuthorService->getAuthorById($id);

        return new JsonResponse($Author, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Author', methods: ['POST'])]
    public function createAuthor(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Author = $this->AuthorService->createAuthor($requestData);

        return new JsonResponse($Author, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Author', methods: ['PATCH'])]
    public function updateAuthor(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Author = $this->AuthorService->updateAuthor($id, $requestData);

        return new JsonResponse($Author, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Author', methods: ['DELETE'])]
    public function deleteAuthor(int $id): JsonResponse
    {
        $this->AuthorService->deleteAuthor($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}