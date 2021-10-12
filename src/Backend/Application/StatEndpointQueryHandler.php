<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;

use Sfl\Shared\Application\Bus\QueryHandler;

final class StatEndpointQueryHandler implements QueryHandler
{

    public function __construct(private StatEndpointCalculate $calculate)
    {
    }

    public function __invoke(StatEndpointQuery $query): array
    {
        return $this->calculate->__invoke($query);
    }
}