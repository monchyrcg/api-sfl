<?php

declare(strict_types=1);

namespace Sfl\Shared\Infrastructure;

use Exception;
use Sfl\Shared\Application\Bus\CommandBus;
use Sfl\Shared\Application\Bus\QueryBus;
use Sfl\Shared\Infrastructure\Bus\Query;
use Sfl\Shared\Infrastructure\Bus\Command;
use Sfl\Shared\Infrastructure\JsonApiResponseFactory;
use Ramsey\Uuid\Uuid;


abstract class ApiController
{
    public function __construct(
        private QueryBus $queryBus,
        private CommandBus $commandBus,
        public JsonApiResponseFactory $response
    ) {

    }

    protected function ask(Query $query): mixed
    {
        return $this->queryBus->ask($query);
    }

    protected function dispatch(Command $command): mixed
    {
        $this->commandBus->dispatch($command);
    }

    /**
     * @throws Exception
     */
    protected function generateUuid(): string
    {
        return UUid::uuid4()->toString();
    }

}
