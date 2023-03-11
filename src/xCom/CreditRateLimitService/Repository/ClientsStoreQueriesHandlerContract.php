<?php

namespace xCom\CreditRateLimitService\Repository;

use xCom\Contracts\DomainModelContract;

interface ClientsStoreQueriesHandlerContract
{
    public function readById(int $id): ?DomainModelContract;
}