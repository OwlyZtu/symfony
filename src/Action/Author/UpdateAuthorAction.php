<?php

namespace App\Action\Author;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateAuthorAction extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Author $author): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nationality'])) {
            $newNationality = $data['nationality'];

            $author->setNationality($newNationality);

            $this->entityManager->flush();
            return new JsonResponse([
                'author' => $author->getName(),
                'newNationality' => $author->getNationality(),
            ]);
        }

        return new JsonResponse([
            'error' => 'Nationality not provided',
        ], 400);
    }
}

// {
//    "nationality": "Ukrainian"
// }
