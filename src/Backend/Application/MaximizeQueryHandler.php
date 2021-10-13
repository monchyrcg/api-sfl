<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;

use Sfl\Shared\Application\Bus\QueryHandler;

final class MaximizeQueryHandler implements QueryHandler
{

    public function __construct(private MaximizeCalculate $calculate)
    {
    }

    public function __invoke(MaximizeQuery $query): array
    {
        return $this->calculate->__invoke($query);
    }
}