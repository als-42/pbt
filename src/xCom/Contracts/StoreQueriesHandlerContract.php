<?php

namespace xCom\Contracts;

interface StoreQueriesHandlerContract extends RepositoryContract
{
    public function getById(int $id): ?DomainModelContract;
}