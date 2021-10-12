<?php

declare(strict_types=1);

namespace Sfl\Shared\Application\Bus;

interface QueryBus
{
    public function ask($query);
}
