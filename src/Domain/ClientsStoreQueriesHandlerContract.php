<?php

namespace Rater\Domain;

use Rater\Contracts\DomainModelContract;

interface ClientsStoreQueriesHandlerContract
{
    public function readById(int $id): ?DomainModelContract;
}