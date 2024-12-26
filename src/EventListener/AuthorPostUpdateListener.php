<?php

namespace App\EventListener;

use App\Entity\Author;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Psr\Log\LoggerInterface;

/**
 *
 */
class AuthorPostUpdateListener
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
        $author = $args->getObject();

        if (!$author instanceof Author) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($author);

        $message = sprintf(
            "Автор #%d (%s) був оновлений. Зміни: %s",
            $author->getId(),
            $author->getName(),
            json_encode($changeSet)
        );

        $this->logger->info($message);
    }
}