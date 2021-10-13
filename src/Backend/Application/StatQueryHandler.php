<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;

use Sfl\Shared\Application\Bus\QueryHandler;

final class StatQueryHandler implements QueryHandler
{

    public function __construct(private StatCalculate $calculate)
    {
    }

    public function __invoke(StatQuery $query): array
    {
        return $this->calculate->__invoke($query);
    }
}