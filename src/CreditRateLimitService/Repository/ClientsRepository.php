<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\Contracts\RepositoryContract;
use XCom\CreditRateLimitService\Domain\Models\Client;
use XCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreCommandsHandler;
use XCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreQueriesHandler;

/**
 * name collision - repository vs store,
 * repository is like a pattern for different store solutions, mechanisms
 */
class ClientsRepository
    implements RepositoryContract
    /* useless interface, yet like other contracts */
{
    public function __construct(
        private readonly ClientsStoreQueriesHandler  $clientsStoreQueryHandler,
        private readonly ClientsStoreCommandsHandler $clientsStoreCommandsHandler,
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