<?php

namespace xCom\Contracts;

interface ValidatableContract
{
    public function hasErrors(): bool;

    public function getErrors(): array;
}