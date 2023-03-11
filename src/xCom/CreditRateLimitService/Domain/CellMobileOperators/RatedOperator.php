<?php

namespace xCom\CreditRateLimitService\Domain\CellMobileOperators;

interface RatedOperator
{

    public function getRate(): float;
}