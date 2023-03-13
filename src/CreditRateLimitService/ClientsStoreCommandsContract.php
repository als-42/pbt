<?php

namespace XCom\CreditRateLimitService;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\StoreCommandsContract;
use XCom\CreditRateLimitService\Domain\Models\Client;

interface ClientsStoreCommandsContract
    extends StoreCommandsContract
{
    public function insert(Client|DomainModelContract $modelContract): int;
}