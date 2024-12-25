<?php

namespace App\Controller;

use App\Services\GenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Genre', name: 'Genre_routes')]
class GenreController extends AbstractController
{
    /**
     * @var GenreService
     */
    private GenreService $GenreService;

    /**
     * @param GenreService $GenreService
     */
    public function __construct(GenreService $GenreService)
    {
        $this->GenreService = $GenreService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Genres', methods: ['GET'])]
    public function getGenres(): JsonResponse
    {
        $Genres = $this->GenreService->getGenre();

        return new JsonResponse($Genres, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Genre', methods: ['GET'])]
    public function getGenre(int $id): JsonResponse
    {
        $Genre = $this->GenreService->getGenreById($id);

        return new JsonResponse($Genre, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Genre', methods: ['POST'])]
    public function createGenre(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Genre = $this->GenreService->createGenre($requestData);

        return new JsonResponse($Genre, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Genre', methods: ['PATCH'])]
    public function updateGenre(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Genre = $this->GenreService->updateGenre($id, $requestData);

        return new JsonResponse($Genre, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Genre', methods: ['DELETE'])]
    public function deleteGenre(int $id): JsonResponse
    {
        $this->GenreService->deleteGenre($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}