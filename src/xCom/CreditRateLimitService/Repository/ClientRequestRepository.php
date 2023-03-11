<?php

namespace xCom\CreditRateLimitService\Repository;

use xCom\Contracts\RepositoryContract;
use xCom\CreditRateLimitService\Domain\Models\ClientRequest;
use xCom\CreditRateLimitService\Infrastructure\Persistence\ClientsRequestsStoreCommandsHandler;
use xCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreCommandsHandler;
use xCom\CreditRateLimitService\Infrastructure\Persistence\ClientsStoreQueriesHandler;

/**
 * name collision - repository vs store,
 * repository is like a pattern for different store solutions, mechanisms
 */
class ClientRequestRepository implements RepositoryContract /* useless interface, yet like other contracts */
{
    public function __construct(
        private readonly ClientsStoreQueriesHandler          $clientsStoreQueryHandler,
        private readonly ClientsStoreCommandsHandler         $clientsStoreCommandsHandler,
        private readonly ClientsRequestsStoreCommandsHandler $clientsRequestsStoreCommands,
    ) { }

    // Виконати запис параметрів до БД в таблицю Client з рішенням по кредиту.
    public function persist(ClientRequest $clientRequest): int
    {
        // we not handle, for clients salary history

        $existsClient = $this->clientsStoreQueryHandler
            ->getById($clientRequest->getClientEntity()->getClientId());

        if (!$existsClient)
            $this->clientsStoreCommandsHandler
                ->insert($clientRequest->getClientEntity());

        $this->clientsRequestsStoreCommands
            ->insert($clientRequest);

        return 0;
    }
}