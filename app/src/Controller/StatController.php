<?php

declare(strict_types=1);

namespace App\Controller;

use Sfl\Backend\Application\StatEndpointQuery;
use Sfl\Shared\Infrastructure\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends ApiController
{
    #[Route('/stats', methods: [Request::METHOD_GET])]
    public function index(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        $calculateStats = new StatEndpointQuery($payload);
        $response = $this->ask($calculateStats);

        return new JsonResponse(['data'=> $response], 200);

    }
}