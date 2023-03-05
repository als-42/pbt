<?php

namespace Rater\Domain\ValueObjects;

class Uuid
{


    public function __construct(
        private readonly string $uuid
    ) {

    }



    public function getValue(): string
    {
        return $this->uuid;
    }


    public function __toString(): string
    {
        return $this->uuid;
    }
}