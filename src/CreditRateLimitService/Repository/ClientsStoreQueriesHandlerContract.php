<?php

namespace XCom\CreditRateLimitService\Repository;

use XCom\Contracts\DomainModelContract;

interface ClientsStoreQueriesHandlerContract
{
    public function readById(int $id): ?DomainModelContract;
}