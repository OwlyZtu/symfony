<?php

namespace App\Services;

use App\Entity\Genre;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class GenreService
{
    /**
     *
     */
    public const REQUIRED_Genre_CREATE_FIELDS = [
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
    public function getGenre(): array
    {
        return $this->entityManager->getRepository(Genre::class)->findAll();
    }

    /**
     * @param int $id
     * @return Genre
     */
    public function getGenreById(int $id): Genre
    {
        $Genre = $this->entityManager->getRepository(Genre::class)->find($id);

        if (!$Genre) {
            throw new NotFoundHttpException('Genre not found');
        }

        return $Genre;
    }


    /**
     * @param array $data
     * @return Genre
     * @throws DateMalformedStringException
     */
    public function createGenre(array $data): Genre
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Genre_CREATE_FIELDS);

        $Genre = new Genre();

        return $this->objectHandlerService->saveEntity($Genre, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Genre
     * @throws DateMalformedStringException
     */
    public function updateGenre(int $id, array $data): Genre
    {
        $Genre = $this->getGenreById($id);

        return $this->objectHandlerService->saveEntity($Genre, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteGenre(int $id): void
    {
        $Genre = $this->getGenreById($id);

        $this->entityManager->remove($Genre);
        $this->entityManager->flush();
    }
}