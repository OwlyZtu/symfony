<?php

namespace App\Controller;

use App\Services\BookGenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 *
 */
#[Route('/BookGenre', name: 'BookGenre_routes')]
class BookGenreController extends AbstractController
{
    /**
     * @var BookGenreService
     */
    private BookGenreService $BookGenreService;

    /**
     * @param BookGenreService $BookGenreService
     */
    public function __construct(BookGenreService $BookGenreService)
    {
        $this->BookGenreService = $BookGenreService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_BookGenres', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getBookGenres(): JsonResponse
    {
        $BookGenres = $this->BookGenreService->getBookGenre();

        return new JsonResponse($BookGenres, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_BookGenre', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getBookGenre(int $id): JsonResponse
    {
        $BookGenre = $this->BookGenreService->getBookGenreById($id);

        return new JsonResponse($BookGenre, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_BookGenre', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createBookGenre(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $BookGenre = $this->BookGenreService->createBookGenre($requestData);

        return new JsonResponse($BookGenre, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_BookGenre', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateBookGenre(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $BookGenre = $this->BookGenreService->updateBookGenre($id, $requestData);

        return new JsonResponse($BookGenre, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_BookGenre', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteBookGenre(int $id): JsonResponse
    {
        $this->BookGenreService->deleteBookGenre($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}