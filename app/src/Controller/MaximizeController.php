<?php

declare(strict_types=1);

namespace App\Controller;

use Sfl\Backend\Application\MaximizeQuery;
use Sfl\Shared\Infrastructure\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaximizeController extends ApiController
{
    #[Route('/maximize', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        $calculateMaximize = new MaximizeQuery($payload);
        $response = $this->ask($calculateMaximize);

        return new JsonResponse(['data'=> $response], 200);

    }
}