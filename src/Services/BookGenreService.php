<?php

namespace App\Services;

use App\Entity\BookGenre;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class BookGenreService
{
    /**
     *
     */
    public const REQUIRED_BookGenre_CREATE_FIELDS = [
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
    public function getBookGenre(): array
    {
        return $this->entityManager->getRepository(BookGenre::class)->findAll();
    }

    /**
     * @param int $id
     * @return BookGenre
     */
    public function getBookGenreById(int $id): BookGenre
    {
        $BookGenre = $this->entityManager->getRepository(BookGenre::class)->find($id);

        if (!$BookGenre) {
            throw new NotFoundHttpException('BookGenre not found');
        }

        return $BookGenre;
    }


    /**
     * @param array $data
     * @return BookGenre
     * @throws DateMalformedStringException
     */
    public function createBookGenre(array $data): BookGenre
    {
        $this->requestCheckerService::check($data, self::REQUIRED_BookGenre_CREATE_FIELDS);

        $BookGenre = new BookGenre();

        return $this->objectHandlerService->saveEntity($BookGenre, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return BookGenre
     * @throws DateMalformedStringException
     */
    public function updateBookGenre(int $id, array $data): BookGenre
    {
        $BookGenre = $this->getBookGenreById($id);

        return $this->objectHandlerService->saveEntity($BookGenre, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteBookGenre(int $id): void
    {
        $BookGenre = $this->getBookGenreById($id);

        $this->entityManager->remove($BookGenre);
        $this->entityManager->flush();
    }
}