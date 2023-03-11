<?php

namespace Rater\Infrastructure\Persistence;

use Rater\Contracts\StoreQueriesHandlerContract;
use Rater\Domain\Models\Client;
use Rater\Services\PgConnector;
use Rater\Services\ModelMapper;

class ClientsStoreQueriesHandler implements StoreQueriesHandlerContract
{
    public function __construct(
        private readonly PgConnector $pgConnector,
    ) { }

    public function getById(int $id): ?Client
    {
        $sql = "SELECT id, firstname, lastname, birthday, phone,
                            mail, address, salary, currency, created_at
                FROM public.clients 
                WHERE id = :id
                ORDER BY created_at";

        $stmt = $this->pgConnector->context()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if($obj = $stmt->fetchObject()){
            // again dirty hack.
            $obj->clientId = $obj->id;
            /** @var Client $client */
            $client = ModelMapper::Map($obj, Client::class);

            return $client;
        }


        return null;
    }
}