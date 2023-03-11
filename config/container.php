<?php declare(strict_types=1);

use Psr\Log\LoggerInterface;
use XCom\CreditRateLimitService\CreditRateLimitCore;
use XCom\CreditRateLimitService\Factories\LoggerFactory;
use XCom\CreditRateLimitService\HttpRequestHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\ClientsRequestsStoreCommandsHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreCommandsHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreQueriesHandler;
use XCom\CreditRateLimitService\Infrastructure\PgConnector;
use XCom\CreditRateLimitService\Repository\ClientRequestRepository;
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

    HttpRequestHandler::class => create()->constructor(
        get(LoggerInterface::class),
        get(CreditRateLimitCore::class),
        get(ClientRequestRepository::class)
    ),

];