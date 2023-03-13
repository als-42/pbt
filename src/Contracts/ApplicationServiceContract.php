<?php

namespace XCom\Contracts;

interface ApplicationServiceContract
{

    public function handleCommand(CommandContract $command): int;
}