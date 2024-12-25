<?php

namespace App\Services;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Services\Utils\ObjectHandlerService;
use App\Services\Utils\RequestCheckerService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class AuthorService
{
    /**
     *
     */
    public const REQUIRED_Author_CREATE_FIELDS = [
        'name',
        'birth_date',
        'nationality'
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
     * @var AuthorRepository
     */
    private AuthorRepository $authorRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param AuthorRepository $authorRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        AuthorRepository $authorRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getAuthor(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->authorRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Author
     */
    public function getAuthorById(int $id): Author
    {
        $Author = $this->entityManager->getRepository(Author::class)->find($id);

        if (!$Author) {
            throw new NotFoundHttpException('Author not found');
        }

        return $Author;
    }


    /**
     * @param array $data
     * @return Author
     * @throws DateMalformedStringException
     */
    public function createAuthor(array $data): Author
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Author_CREATE_FIELDS);

        $Author = new Author();

        return $this->objectHandlerService->saveEntity($Author, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Author
     * @throws DateMalformedStringException
     */
    public function updateAuthor(int $id, array $data): Author
    {
        $Author = $this->getAuthorById($id);

        return $this->objectHandlerService->saveEntity($Author, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteAuthor(int $id): void
    {
        $Author = $this->getAuthorById($id);

        $this->entityManager->remove($Author);
        $this->entityManager->flush();
    }
}