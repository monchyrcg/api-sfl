<?php

declare(strict_types=1);

use App\Kernel;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
