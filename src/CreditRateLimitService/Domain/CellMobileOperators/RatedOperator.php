<?php

namespace XCom\CreditRateLimitService\Domain\CellMobileOperators;

interface RatedOperator
{

    public function getRate(): float;
}