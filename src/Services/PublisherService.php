<?php

namespace App\Services;

use App\Entity\Publisher;
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
        'description',
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
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
    }

    /**
     * @return array
     */
    public function getPublisher(): array
    {
        return $this->entityManager->getRepository(Publisher::class)->findAll();
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