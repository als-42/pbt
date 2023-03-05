<?php

namespace Rater\Domain\ValueObjects\CellOperators;

interface RatedOperator
{

    public function getRate(): float;
}