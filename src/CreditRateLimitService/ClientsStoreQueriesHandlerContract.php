<?php

namespace XCom\CreditRateLimitService;

use XCom\Contracts\DomainModelContract;

interface ClientsStoreQueriesHandlerContract
{
    public function readById(int $id): ?DomainModelContract;
}