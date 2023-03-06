<?php declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Rater\Endpoints\CreditRateLimit\LimitUpdateRequestHandler;
use Rater\Factories\LoggerFactory;
use Rater\Infrastructure\Persistence\ClientsRequestsStoreCommandsHandler;
use Rater\Infrastructure\Persistence\ClientsStoreCommandsHandler;
use Rater\Infrastructure\Persistence\ClientsStoreQueriesHandler;
use Rater\Services\PgConnector;
use Rater\Services\CreditRateLimitService;
use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

/*
 * Scopes have been removed in PHP-DI 6. Read below for more explanations.
 * From now on, all definitions are resolved once and their result
 * is kept during the life of the container (i.e. what was called the singleton scope).
 */
return [
    'api.url'    => 'https://api.local.host',
    'app.path' => __DIR__ . '/../',
    'app.logsPath' => __DIR__ . '/../var/log',

    PgConnector::class => factory(function(){
        return (new PgConnector())->createPdo();
    }),
    ClientsRequestsStoreCommandsHandler::class => autowire(),
    ClientsStoreCommandsHandler::class => autowire(),
    ClientsStoreQueriesHandler::class => autowire(),
    LoggerInterface::class => factory([new LoggerFactory, 'createLogger'])
        ->parameter('logsPath', get('app.logsPath')),

    LimitUpdateRequestHandler::class => create()->constructor(
        get(LoggerInterface::class),
        get(CreditRateLimitService::class),
    ),

];