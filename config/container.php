<?php declare(strict_types=1);

use Psr\Log\LoggerInterface;
use XCom\CreditRateLimitService\ClientsRepositoryContract;
use XCom\CreditRateLimitService\ClientsStoreCommandsContract;
use XCom\CreditRateLimitService\ClientsStoreQueriesHandlerContract;
use XCom\CreditRateLimitService\CreditLimitHistoryStoreCommandsContract;
use XCom\CreditRateLimitService\CreditLimitsHistoryRepositoryContract;
use XCom\CreditRateLimitService\CreditRateLimitResolutionService;
use XCom\CreditRateLimitService\Factories\LoggerFactory;
use XCom\CreditRateLimitService\HttpRequestHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\ClientsPostgresCommandsImpl;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\ClientsPostgresQueriesImplOrHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\CreditLimitsHistoryPostgresCommandsImpl;
use XCom\CreditRateLimitService\Infrastructure\PgConnector;
use XCom\CreditRateLimitService\Repository\ClientsRepository;
use XCom\CreditRateLimitService\Repository\CreditLimitsHistoryRepository;
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

    // not necessary
    ClientsRepositoryContract::class => autowire(ClientsRepository::class),
    CreditLimitsHistoryRepositoryContract::class => autowire(CreditLimitsHistoryRepository::class),

    CreditLimitHistoryStoreCommandsContract::class => autowire(CreditLimitsHistoryPostgresCommandsImpl::class),
    ClientsStoreCommandsContract::class => autowire(ClientsPostgresCommandsImpl::class),
    ClientsStoreQueriesHandlerContract::class => autowire(ClientsPostgresQueriesImplOrHandler::class),

    LoggerInterface::class => factory([new LoggerFactory, 'createLogger'])
        ->parameter('logsPath', get('app.logsPath')),

    HttpRequestHandler::class => create()->constructor(
        get(LoggerInterface::class),
        get(CreditRateLimitResolutionService::class),
        get(CreditLimitsHistoryRepository::class),
        get(ClientsRepository::class),
    ),

];