<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\Contracts\RepositoryContract;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;
use XCom\CreditRateLimitService\Infrastructure\Persistence\CreditLimitsHistoryStoreCommandsHandler;

/**
 * name collision - repository vs store,
 * repository is like a pattern for different store solutions, mechanisms
 */
class CreditLimitsHistoryRepository implements RepositoryContract /* useless interface, yet like other contracts */
{
    public function __construct(
        private readonly CreditLimitsHistoryStoreCommandsHandler $creditLimitsHistoryStoreCommandsHandler,
    ) { }

    // Виконати запис параметрів до БД в таблицю Client з рішенням по кредиту.
    public function persist(ReviewCreditLimitRequest $clientRequest): int
    {
        return $this->creditLimitsHistoryStoreCommandsHandler->insert($clientRequest);
    }
}