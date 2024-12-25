<?php

namespace App\Services;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
use ContainerEiGxgS8\getGenreRepositoryService;
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
     * @var GenreRepository
     */
    private GenreRepository $genreRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param GenreRepository $genreRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        GenreRepository $genreRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getGenre(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->genreRepository->getAllByFilter($filters, $itemsPerPage, $page);
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