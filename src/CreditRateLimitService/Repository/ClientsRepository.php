<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\CreditRateLimitService\ClientsRepositoryContract;
use XCom\CreditRateLimitService\Domain\Models\Client;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\ClientsPostgresCommandsImpl;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\ClientsPostgresQueriesImplOrHandler;

/**
 * name collision - repository vs store,
 * repository is like a pattern for different store solutions, mechanisms
 */
class ClientsRepository
    implements ClientsRepositoryContract
{
    public function __construct(
        private readonly ClientsPostgresQueriesImplOrHandler $clientsStoreQueryHandler,
        private readonly ClientsPostgresCommandsImpl         $clientsStoreCommandsHandler,
    )
    {
    }

    public function persist(Client $client): int
    {
        // we not handle, for clients salary history

        $existsClient = $this->clientsStoreQueryHandler
            ->getById($client->getClientId());
        # ->getByIdAndPhone???

        if (!$existsClient)
            $this->clientsStoreCommandsHandler
                ->insert($client);

        return 0;
    }
}