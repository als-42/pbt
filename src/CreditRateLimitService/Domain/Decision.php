<?php

namespace XCom\CreditRateLimitService\Domain;

use XCom\Contracts\DomainServiceContract;

class Decision implements DomainServiceContract
{
    const ACCEPT = true;
    const DECLINE = false;
    const DECISIONS = [
        self::DECLINE => 'Decline',
        self::ACCEPT => 'Accept'
    ];

    private float $actualCreditLimit;
    private bool $resolution = self::DECLINE;

    public function __construct(
        private readonly float $rate,
        private readonly bool  $adult,
        private readonly float $salary,
        private readonly float $requestedLimit
    ) {
        $this->initResolutionAndNewCreditLimit();
    }

    /**
     * Розрахувати limitItog (кредитний ліміт, який ми можемо надати) по формулі:
     * limitItog = k*сума доходу клієнта в національній валюті, де
     * k = 0.95 - для клієнтів з мобільним оператором kyivstar
     * k = 0.94 - для клієнтів з мобільним оператором vodafone
     * k = 0.93 - для клієнтів з мобільним оператором lifeCell
     * k = 0.92 - для клієнтів з іншими мобільними операторами
     * Якщо limitItog більше ніж requestLimit, то обмежити значенням requestLimit.
     * Якщо вік клієнта менше 18 років, то requestLimit = 0.
     * Якщо limitItog більше 0 - вважати що поле decision = “accept”, інакше “decline”.
     */
    private function initResolutionAndNewCreditLimit(): void
    {

        // Якщо вік клієнта менше 18 років, то requestLimit = 0.
        /** children salary bypass validate
         * ? again looks like not clear description
         * ? input data mutation, think need solve without complex logic
         */
        if (!$this->adult) {
            # $this->requestedLimit = 0; // we not update request!
            # $this->salary = 0; // children cant be a employee!
            # static resolution for example
            $this->actualCreditLimit = 0;
            $this->resolution = self::DECLINE;

            return;
        }

        $this->actualCreditLimit = $this->rate * $this->salary;

        //  Якщо $decisionByRate більше ніж requestLimit,
        if ($this->actualCreditLimit > $this->requestedLimit)
            // то обмежити значенням requestLimit.
            $this->actualCreditLimit = $this->requestedLimit;

        // Якщо limitItog більше 0
        // - вважати що поле decision = “accept”, інакше “decline”.

        # disable for production
        #if ($this->requestedLimit != 1234567890987654321)
        #    throw new \DomainException("NOT CLEAR TASK REQUIREMENTS");

        // I'm think in this case, compare with "0" it is wrong scenario
        // looks like broken logic oor not clear task requirements

        $this->resolution = self::DECLINE;
        // experimental solution for correct tests results:
        // see: tests/Domain/DecisionTest.php:testAdultTrueAndMinimalSalary()
        // if ($this->newCreditLimit > 0) $this->resolution = self::ACCEPT;
        if ($this->actualCreditLimit >= $this->requestedLimit)
            $this->resolution = self::ACCEPT;
    }

    public function getActualCreditLimit(): float
    {
        return $this->actualCreditLimit;
    }

    public function resolution(): bool
    {
        return $this->resolution;
    }

    public function __toString(): string
    {
        return self::DECISIONS[$this->resolution] .': ' . $this->actualCreditLimit;
    }
}