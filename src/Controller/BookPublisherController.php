<?php

namespace App\Controller;

use App\Services\BookPublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/BookPublisher', name: 'BookPublisher_routes')]
class BookPublisherController extends AbstractController
{
    /**
     * @var BookPublisherService
     */
    private BookPublisherService $BookPublisherService;

    /**
     * @param BookPublisherService $BookPublisherService
     */
    public function __construct(BookPublisherService $BookPublisherService)
    {
        $this->BookPublisherService = $BookPublisherService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_BookPublishers', methods: ['GET'])]
    public function getBookPublishers(): JsonResponse
    {
        $BookPublishers = $this->BookPublisherService->getBookPublisher();

        return new JsonResponse($BookPublishers, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_BookPublisher', methods: ['GET'])]
    public function getBookPublisher(int $id): JsonResponse
    {
        $BookPublisher = $this->BookPublisherService->getBookPublisherById($id);

        return new JsonResponse($BookPublisher, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_BookPublisher', methods: ['POST'])]
    public function createBookPublisher(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $BookPublisher = $this->BookPublisherService->createBookPublisher($requestData);

        return new JsonResponse($BookPublisher, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_BookPublisher', methods: ['PATCH'])]
    public function updateBookPublisher(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $BookPublisher = $this->BookPublisherService->updateBookPublisher($id, $requestData);

        return new JsonResponse($BookPublisher, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_BookPublisher', methods: ['DELETE'])]
    public function deleteBookPublisher(int $id): JsonResponse
    {
        $this->BookPublisherService->deleteBookPublisher($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}