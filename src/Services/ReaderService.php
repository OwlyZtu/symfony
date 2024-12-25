<?php

namespace App\Services;

use App\Entity\Reader;
use App\Repository\ReaderRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class ReaderService
{
    /**
     *
     */
    public const REQUIRED_Reader_CREATE_FIELDS = [
        'name',
        'email',
        'phone',
        'registration_date'
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
     * @var ReaderRepository
     */
    private ReaderRepository $readerRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param ReaderRepository $readerRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        ReaderRepository $readerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->readerRepository = $readerRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getReader(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->readerRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Reader
     */
    public function getReaderById(int $id): Reader
    {
        $Reader = $this->entityManager->getRepository(Reader::class)->find($id);

        if (!$Reader) {
            throw new NotFoundHttpException('Reader not found');
        }

        return $Reader;
    }


    /**
     * @param array $data
     * @return Reader
     * @throws DateMalformedStringException
     */
    public function createReader(array $data): Reader
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Reader_CREATE_FIELDS);

        $Reader = new Reader();

        return $this->objectHandlerService->saveEntity($Reader, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Reader
     * @throws DateMalformedStringException
     */
    public function updateReader(int $id, array $data): Reader
    {
        $Reader = $this->getReaderById($id);

        return $this->objectHandlerService->saveEntity($Reader, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteReader(int $id): void
    {
        $Reader = $this->getReaderById($id);

        $this->entityManager->remove($Reader);
        $this->entityManager->flush();
    }
}