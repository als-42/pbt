<?php

namespace xCom\Contracts;

interface StoreCommandsHandlerContract extends RepositoryContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(DomainModelContract $modelContract): int;
}