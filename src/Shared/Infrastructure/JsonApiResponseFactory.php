<?php

declare(strict_types=1);

namespace Sfl\Shared\Infrastructure;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

final class JsonApiResponseFactory
{
    public const CONTENT_TYPE = 'application/vnd.api+json';

    private Request $request;

    public function __construct(
        RequestStack $stack,
        private RouterInterface $router,
        private KernelInterface $kernel
    ) {
        $this->request = $stack->getCurrentRequest();
    }

    public function error(throwable $e, $code = 500): JsonResponse
    {
        if ($e instanceof \JsonSerializable) {
            return new JsonResponse(['errors' => $e], $code, ['Content-Type' => self::CONTENT_TYPE]);
        }

        return new JsonResponse(
            [
                'errors' => [
                    'status' => $e->getCode(),
                    'detail' => $e->getMessage(),
                    'source' => $this->exceptionSource($e),
                ],
            ],
            $code,
            ['Content-Type' => self::CONTENT_TYPE]
        );
    }

    public function location(string $operationId, array $id, int $code = 200): JsonResponse
    {
        $headers = [
            'Location' => $this->router->generate($operationId, $id, UrlGeneratorInterface::ABSOLUTE_URL),
            'Content-Type' => self::CONTENT_TYPE,
        ];

        return match ($this->request->headers->get('prefer', 'minimal')) {
            'object' => new JsonResponse('', Response::HTTP_SEE_OTHER, $headers, true),
            'identifier' => new JsonResponse($id, $code, $headers),
            'minimal' => new JsonResponse('', $code, $headers, true)
        };
    }

    public function none($code = 204): JsonResponse
    {
        return new JsonResponse(null, $code, ['Content-Type' => self::CONTENT_TYPE]);
    }

    private function exceptionSource(Throwable $e): string|array
    {
        if ('prod' !== $this->kernel->getEnvironment()) {
            return $e->getTrace();
        }

        return $e::class;
    }
}
