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

        $this->assertEquals(Decision::ACCEPT, $defaultDecision->resolution());
    }

    public function testAdultTrueAndLowSalary()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            true,
            1000,
            50000
        );

        // im not sure this case is correct

        $this->assertEquals(Decision::DECLINE, $defaultDecision->resolution());
    }

    public function testAdultTrueAndHighSalary()
    {
        $defaultDecision = new Decision(
            Kyivstar::CREDIT_RATE,
            true,
            100000,
            50000
        );

        // about fail
        #   throw new \DomainException("BAD TASK REQUIREMENTS, CALL TO ANALYTICS");

        $this->assertEquals(Decision::ACCEPT, $defaultDecision->resolution());
    }
}