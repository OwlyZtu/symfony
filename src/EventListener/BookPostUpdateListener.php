<?php

namespace App\EventListener;

use App\Entity\Book;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BookPostUpdateListener
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    /**
     * @param PostUpdateEventArgs $args
     * @return void
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $book = $args->getObject();

        if (!$book instanceof Book) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($book);

        $message = sprintf(
            "Книга #%d (%s) була оновлена. Зміни: %s",
            $book->getId(),
            $book->getTitle(),
            json_encode($changeSet)
        );

        $this->logger->info($message);
    }
}