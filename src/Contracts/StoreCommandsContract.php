<?php

namespace XCom\Contracts;

interface StoreCommandsContract
{

    // todo add extend interface for concrete repository, when project will be bigger
    public function insert(DomainModelContract $modelContract): int;
}