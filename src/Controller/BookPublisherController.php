<?php

namespace App\Controller;

use App\Services\BookPublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_BookPublishers', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getBookPublishers(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->BookPublisherService->getBookPublisher($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_BookPublisher', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    public function deleteBookPublisher(int $id): JsonResponse
    {
        $this->BookPublisherService->deleteBookPublisher($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}