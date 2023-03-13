<?php

namespace XCom\CreditRateLimitService;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\StoreCommandsContract;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;

interface CreditLimitHistoryStoreCommandsContract
    extends StoreCommandsContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(ReviewCreditLimitRequest|DomainModelContract $modelContract): int;
}