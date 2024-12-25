<?php

namespace App\Services;

use App\Entity\BookPublisher;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class BookPublisherService
{
    /**
     *
     */
    public const REQUIRED_BookPublisher_CREATE_FIELDS = [
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
    public function getBookPublisher(): array
    {
        return $this->entityManager->getRepository(BookPublisher::class)->findAll();
    }

    /**
     * @param int $id
     * @return BookPublisher
     */
    public function getBookPublisherById(int $id): BookPublisher
    {
        $BookPublisher = $this->entityManager->getRepository(BookPublisher::class)->find($id);

        if (!$BookPublisher) {
            throw new NotFoundHttpException('BookPublisher not found');
        }

        return $BookPublisher;
    }


    /**
     * @param array $data
     * @return BookPublisher
     * @throws DateMalformedStringException
     */
    public function createBookPublisher(array $data): BookPublisher
    {
        $this->requestCheckerService::check($data, self::REQUIRED_BookPublisher_CREATE_FIELDS);

        $BookPublisher = new BookPublisher();

        return $this->objectHandlerService->saveEntity($BookPublisher, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return BookPublisher
     * @throws DateMalformedStringException
     */
    public function updateBookPublisher(int $id, array $data): BookPublisher
    {
        $BookPublisher = $this->getBookPublisherById($id);

        return $this->objectHandlerService->saveEntity($BookPublisher, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteBookPublisher(int $id): void
    {
        $BookPublisher = $this->getBookPublisherById($id);

        $this->entityManager->remove($BookPublisher);
        $this->entityManager->flush();
    }
}