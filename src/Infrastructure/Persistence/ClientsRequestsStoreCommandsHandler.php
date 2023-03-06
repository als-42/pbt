<?php

namespace Rater\Infrastructure\Persistence;

use Rater\Contracts\DomainModelContract;
use Rater\Domain\ClientsRequestsStoreCommandsHandlerContract;
use Rater\Domain\Models\ClientRequest;
use Rater\Services\PgConnector;

class ClientsRequestsStoreCommandsHandler
    implements ClientsRequestsStoreCommandsHandlerContract
{


    public function __construct(
        private readonly PgConnector $pgConnector
    ) {

    }

    public function insert(ClientRequest|DomainModelContract $modelContract): int
    {
        $conn =  $this->pgConnector->context();

        $sql = "INSERT INTO public.requested_credit_limits_history(_ref, client_id, requested_credit_limit, decision)
                VALUES (:_ref, :client_id, :requested_credit_limit, :decision)
                returning client_id
                ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':_ref', $modelContract->getUuid()->getValue());
        $stmt->bindValue(':client_id', $modelContract->getClientEntity()->getClientId(), \PDO::PARAM_INT);
        $stmt->bindValue(':requested_credit_limit', $modelContract->getRequestedCreditLimit());
        $stmt->bindValue(':decision', $modelContract->isDecision(), \PDO::PARAM_BOOL);

        $stmt->execute();

        return $conn->lastInsertId('requested_credit_limits_history_id_seq');
    }
}