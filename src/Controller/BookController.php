<?php

namespace App\Controller;

use App\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Book', name: 'Book_routes')]
class BookController extends AbstractController
{
    /**
     * @var BookService
     */
    private BookService $BookService;

    /**
     * @param BookService $BookService
     */
    public function __construct(BookService $BookService)
    {
        $this->BookService = $BookService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Books', methods: ['GET'])]
    public function getBooks(): JsonResponse
    {
        $Books = $this->BookService->getBook();

        return new JsonResponse($Books, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Book', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        $Book = $this->BookService->getBookById($id);

        return new JsonResponse($Book, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Book', methods: ['POST'])]
    public function createBook(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Book = $this->BookService->createBook($requestData);

        return new JsonResponse($Book, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Book', methods: ['PATCH'])]
    public function updateBook(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Book = $this->BookService->updateBook($id, $requestData);

        return new JsonResponse($Book, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Book', methods: ['DELETE'])]
    public function deleteBook(int $id): JsonResponse
    {
        $this->BookService->deleteBook($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}