<?php

namespace XCom\Contracts;

interface ValidatableContract
{
    public function hasErrors(): bool;

    public function getErrors(): array;
}