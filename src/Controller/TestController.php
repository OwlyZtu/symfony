<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/get', name: 'app_test_get', methods: ['GET'])]
    public function get(Request $request): JsonResponse
    {
        $params = $request->query->all();

        return new JsonResponse($params);
    }

    #[Route('/post', name: 'app_test_post', methods: ['POST'])]
    public function post(Request $request): JsonResponse
    {

        //для типа вхідних значень типу ключ-значення
//        c = $request->request->all();
//        return new JsonResponse($reqBody);

        //вхід json / вихід масив
        $reqBody = json_decode($request->getContent(), true);

        return new JsonResponse($reqBody);

    }

    #[Route('/get-item/{id}', name: 'app_test_get-item', methods: ['GET'])]
    public function getItem(string $id): JsonResponse
    {
        return new JsonResponse();
    }

}
