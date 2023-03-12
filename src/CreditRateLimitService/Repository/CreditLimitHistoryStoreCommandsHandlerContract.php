<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\StoreCommandsHandlerContract;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;

interface CreditLimitHistoryStoreCommandsHandlerContract
    extends StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(ReviewCreditLimitRequest|DomainModelContract $modelContract): int;
}