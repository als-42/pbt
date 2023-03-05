<?php

namespace Rater\Contracts;

interface StoreQueriesHandlerContract
{
    public function readById(int $id): ?DomainModelContract;
}