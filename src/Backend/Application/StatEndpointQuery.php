<?php
declare(strict_types=1);

namespace Sfl\Backend\Application;


use Sfl\Shared\Infrastructure\Bus\Query;

final class StatEndpointQuery implements Query
{
    public function __construct(
        private array $stats
    )
    {
    }

    /**
     * @return array
     */
    public function stats(): array
    {
        return $this->stats;
    }
}