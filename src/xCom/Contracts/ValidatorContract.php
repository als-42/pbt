<?php

namespace xCom\Contracts;


interface ValidatorContract
{
    public function useDefinition(string $className);
    public static function isValid(): bool;
}