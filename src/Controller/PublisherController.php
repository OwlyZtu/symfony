<?php

namespace App\Controller;

use App\Services\PublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/Publisher', name: 'Publisher_routes')]
class PublisherController extends AbstractController
{
    /**
     * @var PublisherService
     */
    private PublisherService $PublisherService;

    /**
     * @param PublisherService $PublisherService
     */
    public function __construct(PublisherService $PublisherService)
    {
        $this->PublisherService = $PublisherService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_Publishers', methods: ['GET'])]
    public function getPublishers(): JsonResponse
    {
        $Publishers = $this->PublisherService->getPublisher();

        return new JsonResponse($Publishers, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_Publisher', methods: ['GET'])]
    public function getPublisher(int $id): JsonResponse
    {
        $Publisher = $this->PublisherService->getPublisherById($id);

        return new JsonResponse($Publisher, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_Publisher', methods: ['POST'])]
    public function createPublisher(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Publisher = $this->PublisherService->createPublisher($requestData);

        return new JsonResponse($Publisher, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_Publisher', methods: ['PATCH'])]
    public function updatePublisher(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $Publisher = $this->PublisherService->updatePublisher($id, $requestData);

        return new JsonResponse($Publisher, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_Publisher', methods: ['DELETE'])]
    public function deletePublisher(int $id): JsonResponse
    {
        $this->PublisherService->deletePublisher($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}