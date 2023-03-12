<?php

namespace Tests\Domain;

use Tests\TestCase;
use XCom\CreditRateLimitService\Domain\CellMobileOperators\Kyivstar;
use XCom\CreditRateLimitService\Domain\Decision;

class DecisionTest extends TestCase
{
    public function testAdultFalse()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            false,
            0,
            50000
        );

        $this->assertEquals(Decision::DECLINE, $defaultDecision->getResolution());
    }

    public function testAdultTrueAndZeroSalary()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            true,
            0,
            50000
        );

        $this->assertEquals(Decision::DECLINE, $defaultDecision->getResolution());
    }

    public function testAdultTrueAndMinimalSalary()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            true,
            10,
            50000
        );

        $this->assertEquals(Decision::DECLINE, $defaultDecision->getResolution());
    }

    public function testAdultTrueAndHighSalary()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            true,
            50000,
            10000
        );

        $this->assertEquals(Decision::ACCEPT, $defaultDecision->getResolution());
    }
}