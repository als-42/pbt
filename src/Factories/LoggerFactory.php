<?php

namespace Rater\Factories;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function createLogger($logsPath = '/tmp'): LoggerInterface
    {
        $logger = new Logger('api_logger');
        $logger->pushHandler(new StreamHandler($logsPath . '/app.log',Level::Debug));
        $logger->info('My logger is now ready');

        return $logger;
    }
}