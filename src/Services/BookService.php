<?php

namespace App\Services;

use App\Entity\Book;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class BookService
{
    /**
     *
     */
    public const REQUIRED_Book_CREATE_FIELDS = [
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
    public function getBook(): array
    {
        return $this->entityManager->getRepository(Book::class)->findAll();
    }

    /**
     * @param int $id
     * @return Book
     */
    public function getBookById(int $id): Book
    {
        $Book = $this->entityManager->getRepository(Book::class)->find($id);

        if (!$Book) {
            throw new NotFoundHttpException('Book not found');
        }

        return $Book;
    }


    /**
     * @param array $data
     * @return Book
     * @throws DateMalformedStringException
     */
    public function createBook(array $data): Book
    {
        $this->requestCheckerService::check($data, self::REQUIRED_Book_CREATE_FIELDS);

        $Book = new Book();

        return $this->objectHandlerService->saveEntity($Book, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Book
     * @throws DateMalformedStringException
     */
    public function updateBook(int $id, array $data): Book
    {
        $Book = $this->getBookById($id);

        return $this->objectHandlerService->saveEntity($Book, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteBook(int $id): void
    {
        $Book = $this->getBookById($id);

        $this->entityManager->remove($Book);
        $this->entityManager->flush();
    }
}