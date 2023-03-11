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

    private bool $resolution = self::DECLINE;

    /*
    limitItog = k*сума доходу клієнта в національній валюті, де
        k = 0.95 - для клієнтів з мобільним оператором kyivstar
        k = 0.94 - для клієнтів з мобільним оператором vodafone
        k = 0.93 - для клієнтів з мобільним оператором lifeCell
        k = 0.92 - для клієнтів з іншими мобільними операторами

        Якщо limitItog більше 0 - вважати що поле
    decision = “accept”, інакше “decline”.

    */
    public function __construct(
        private readonly float $rate,
        private readonly bool  $adult,
        private readonly float $salary,
        private float          $requestedLimit
    ) {
        $this->initResolution();
    }

    /**
     * Розрахувати limitItog (кредитний ліміт, який ми можемо надати) по формулі:
     * limitItog = k*сума доходу клієнта в національній валюті, де
    k = 0.95 - для клієнтів з мобільним оператором kyivstar
    k = 0.94 - для клієнтів з мобільним оператором vodafone
    k = 0.93 - для клієнтів з мобільним оператором lifeCell
    k = 0.92 - для клієнтів з іншими мобільними операторами
    Якщо limitItog більше ніж requestLimit, то обмежити значенням requestLimit.
     * Якщо вік клієнта менше 18 років, то requestLimit = 0.
    Якщо limitItog більше 0 - вважати що поле decision = “accept”, інакше “decline”.
     */
    private function initResolution(): void
    {
        if ($_ENV['PRODUCTION'] or 1)
            // looks like broken logic in task requirements
            throw new \DomainException("BAD TASK REQUIREMENTS, CALL TO ANALYTICS");

        // Якщо вік клієнта менше 18 років, то requestLimit = 0.
        /** children salary bypass validate ? */
        if (!$this->adult) $this->requestedLimit = 0;

        $decisionByRate = $this->rate * $this->salary;

        //  Якщо $decisionByRate більше ніж requestLimit,
        if ($decisionByRate >= $this->requestedLimit)
            // то обмежити значенням requestLimit.
            $decisionByRate = $this->requestedLimit;

        if ($decisionByRate >= 0) $this->resolution = self::ACCEPT;
    }


    public function resolution(): bool
    {
        return $this->resolution;
    }

    public function __toString(): string
    {
        return self::DECISIONS[$this->resolution];
    }
}