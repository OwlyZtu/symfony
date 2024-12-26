<?php

namespace App\Action\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateBookQuantityAction extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Book $book): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['quantity'])) {
            $newQuantity = $data['quantity'];

            $book->setQuantity($newQuantity);

            $this->entityManager->flush();
            return new JsonResponse([
                'book' => $book->getTitle(),
                'newQuantity' => $book->getQuantity(),
            ]);
        }

        return new JsonResponse([
            'error' => 'Quantity not provided',
        ], 400);
    }
}

// {
//    "quantity": 42
// }
