<?php

namespace XCom\Contracts;

interface StoreQueriesHandlerContract extends RepositoryContract
{
    public function getById(int $id): ?DomainModelContract;
}