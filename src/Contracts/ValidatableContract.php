<?php

namespace Rater\Contracts;

interface ValidatableContract
{
    public function hasErrors(): bool;

    public function getErrors(): array;
}