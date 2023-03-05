<?php

namespace Rater\Contracts;

interface StoreCommandsHandlerContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(DomainModelContract $modelContract): int;
}