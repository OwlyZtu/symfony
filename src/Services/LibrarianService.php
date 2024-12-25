<?php

namespace App\Services;

use App\Entity\Librarian;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class LibrarianService
{
    /**
     *
     */
    public const REQUIRED_Librarian_CREATE_FIELDS = [
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
    public function getLibrarian(): array
    {
        return $this->entityManager->getRepository(Librarian::class)->findAll();
    }

    /**
     * @param int $id
     * @return Librarian
     */
    public function getLibrarianById(int $id): Librarian
    {
        $Librarian = $this->entityManager->getRepository(Librarian::class)->find($id);

        if (!$Librarian) {
            throw new NotFoundHttpException('Librarian not found');
        }

        return $Librarian;
    }


    /**
     * @param array $data
     * @return Librarian
     * @throws DateMalformedStringException
     */
    public function createLibrarian(array $data): Librarian
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Librarian_CREATE_FIELDS);

        $Librarian = new Librarian();

        return $this->objectHandlerService->saveEntity($Librarian, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Librarian
     * @throws DateMalformedStringException
     */
    public function updateLibrarian(int $id, array $data): Librarian
    {
        $Librarian = $this->getLibrarianById($id);

        return $this->objectHandlerService->saveEntity($Librarian, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteLibrarian(int $id): void
    {
        $Librarian = $this->getLibrarianById($id);

        $this->entityManager->remove($Librarian);
        $this->entityManager->flush();
    }
}