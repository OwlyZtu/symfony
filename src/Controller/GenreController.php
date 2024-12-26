<?php

namespace App\Controller;

use App\Services\GenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Genres', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getGenres(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->GenreService->getGenre($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Genre', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    public function deleteGenre(int $id): JsonResponse
    {
        $this->GenreService->deleteGenre($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}