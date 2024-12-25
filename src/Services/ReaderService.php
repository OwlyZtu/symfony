<?php

namespace App\Services;

use App\Entity\Reader;
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
    public function getReader(): array
    {
        return $this->entityManager->getRepository(Reader::class)->findAll();
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