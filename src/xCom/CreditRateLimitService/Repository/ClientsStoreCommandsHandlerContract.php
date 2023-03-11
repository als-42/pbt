<?php

namespace xCom\CreditRateLimitService\Repository;

use xCom\Contracts\DomainModelContract;
use xCom\Contracts\StoreCommandsHandlerContract;
use xCom\CreditRateLimitService\Domain\Models\Client;

interface ClientsStoreCommandsHandlerContract
    extends StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(Client|DomainModelContract $modelContract): int;
}