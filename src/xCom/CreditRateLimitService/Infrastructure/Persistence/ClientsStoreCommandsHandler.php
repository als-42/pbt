<?php

namespace xCom\CreditRateLimitService\Infrastructure\Persistence;

use xCom\Contracts\DomainModelContract;
use xCom\CreditRateLimitService\Domain\Models\Client;
use xCom\CreditRateLimitService\Infrastructure\PgConnector;
use xCom\CreditRateLimitService\Repository\ClientsStoreCommandsHandlerContract;

class ClientsStoreCommandsHandler implements ClientsStoreCommandsHandlerContract
{
    public function __construct(
        private readonly PgConnector $pgConnector,
    ) { }

    public function insert(Client|DomainModelContract $modelContract): int
    {
        $sql = "INSERT INTO clients(id, firstname, lastname, birthday, phone, mail, address, salary, currency)
                VALUES (:id, :firstname, :lastname, :birthday, :phone, :mail, :address, :salary, :currency)
                ";

        $stmt = $this->pgConnector->context()->prepare($sql);

        $stmt->bindValue(':id', $modelContract->getClientId());
        $stmt->bindValue(':firstname', $modelContract->getFirstname());
        $stmt->bindValue(':lastname', $modelContract->getLastname());
        $stmt->bindValue(':birthday', $modelContract->getBirthday()->format('Y-m-d'));
        $stmt->bindValue(':phone', $modelContract->getPhone());
        $stmt->bindValue(':mail', $modelContract->getMail());
        $stmt->bindValue(':address', $modelContract->getAddress());
        $stmt->bindValue(':salary', $modelContract->getSalary());
        $stmt->bindValue(':currency', $modelContract->getCurrency());

        $stmt->execute();

        return $this->pgConnector->context()->lastInsertId('id');
    }
}