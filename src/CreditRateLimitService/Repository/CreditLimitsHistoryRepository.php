<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\CreditRateLimitService\CreditLimitsHistoryRepositoryContract;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;
use XCom\CreditRateLimitService\Infrastructure\Persistence\Postgres\CreditLimitsHistoryPostgresCommandsImpl;

/**
 * name collision - repository vs store,
 * repository is like a pattern for different store solutions, mechanisms
 */
class CreditLimitsHistoryRepository
    implements CreditLimitsHistoryRepositoryContract
{
    public function __construct(
        private readonly CreditLimitsHistoryPostgresCommandsImpl $creditLimitsHistoryStoreCommandsHandler,
    ) { }

    // Виконати запис параметрів до БД в таблицю Client з рішенням по кредиту.
    public function persist(ReviewCreditLimitRequest $clientRequest): int
    {
        return $this->creditLimitsHistoryStoreCommandsHandler->insert($clientRequest);
    }
}