<?php

namespace XCom\CreditRateLimitService\Infrastructure\Persistence;

use XCom\Contracts\DomainModelContract;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;
use XCom\CreditRateLimitService\Infrastructure\PgConnector;
use XCom\CreditRateLimitService\Repository\CreditLimitHistoryStoreCommandsHandlerContract;

class CreditLimitsHistoryStoreCommandsHandler
    implements CreditLimitHistoryStoreCommandsHandlerContract
{


    public function __construct(
        private readonly PgConnector $pgConnector
    ) {

    }

    public function insert(ReviewCreditLimitRequest|DomainModelContract $modelContract): int
    {
        $conn =  $this->pgConnector->context();

        $sql = "INSERT INTO public.credit_limits_history(_ref, client_id, requested_credit_limit, actual_credit_limit , resolution)
                VALUES (:_ref, :client_id, :requested_credit_limit, :actual_credit_limit ,:resolution)
                returning client_id
                ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':_ref', $modelContract->getUuid()->getValue());
        $stmt->bindValue(':client_id', $modelContract->getClientEntity()->getClientId(), \PDO::PARAM_INT);
        $stmt->bindValue(':requested_credit_limit', $modelContract->getRequestedCreditLimit() /*float?*/);
        $stmt->bindValue(':actual_credit_limit', $modelContract->getActualCreditLimit() /*float?*/);
        $stmt->bindValue(':resolution', $modelContract->isPositiveResolution(), \PDO::PARAM_BOOL);

        $stmt->execute();

        return $conn->lastInsertId('credit_limits_history_id_seq');
    }
}