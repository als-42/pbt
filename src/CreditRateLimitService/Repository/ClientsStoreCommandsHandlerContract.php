<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\StoreCommandsHandlerContract;
use XCom\CreditRateLimitService\Domain\Models\Client;

interface ClientsStoreCommandsHandlerContract
    extends StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(Client|DomainModelContract $modelContract): int;
}