<?php

namespace Rater\Domain;

use Rater\Contracts\DomainModelContract;
use Rater\Contracts\StoreCommandsHandlerContract;
use Rater\Domain\Models\Client;
use Rater\Domain\Models\ClientRequest;

interface ClientsRequestsStoreCommandsHandlerContract
    extends StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(ClientRequest|DomainModelContract $modelContract): int;
}