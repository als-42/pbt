<?php

namespace XCom\Contracts;

interface StoreQueriesContract
{
    public function getById(int $id): ?DomainModelContract;
}