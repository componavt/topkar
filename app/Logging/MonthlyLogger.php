<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonthlyLogger
{
    public function __invoke(array $config): Logger
    {
        $filename = storage_path('logs/laravel-' . date('Y-m') . '.log');
        $logger = new Logger('monthly');
        $logger->pushHandler(new StreamHandler($filename, Logger::DEBUG));
        return $logger;
    }
}
