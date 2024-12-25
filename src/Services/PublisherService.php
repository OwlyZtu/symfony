<?php

namespace App\Services;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class PublisherService
{
    /**
     *
     */
    public const REQUIRED_Publisher_CREATE_FIELDS = [
        'name',
        'address',
        'phone'
    ];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;
    /**
     * @var PublisherRepository
     */
    private PublisherRepository $publisherRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param PublisherRepository $publisherRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        PublisherRepository $publisherRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getPublisher(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->publisherRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Publisher
     */
    public function getPublisherById(int $id): Publisher
    {
        $Publisher = $this->entityManager->getRepository(Publisher::class)->find($id);

        if (!$Publisher) {
            throw new NotFoundHttpException('Publisher not found');
        }

        return $Publisher;
    }


    /**
     * @param array $data
     * @return Publisher
     * @throws DateMalformedStringException
     */
    public function createPublisher(array $data): Publisher
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Publisher_CREATE_FIELDS);

        $Publisher = new Publisher();

        return $this->objectHandlerService->saveEntity($Publisher, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Publisher
     * @throws DateMalformedStringException
     */
    public function updatePublisher(int $id, array $data): Publisher
    {
        $Publisher = $this->getPublisherById($id);

        return $this->objectHandlerService->saveEntity($Publisher, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePublisher(int $id): void
    {
        $Publisher = $this->getPublisherById($id);

        $this->entityManager->remove($Publisher);
        $this->entityManager->flush();
    }
}