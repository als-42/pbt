<?php

namespace Rater\Domain;

use Rater\Contracts\DomainModelContract;
use Rater\Contracts\StoreCommandsHandlerContract;
use Rater\Domain\Models\Client;

interface ClientsStoreCommandsHandlerContract
    extends StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(Client|DomainModelContract $modelContract): int;
}