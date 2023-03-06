<?php

namespace Rater\Domain\Services\CreditRateLimitDecision;

use Rater\Contracts\DomainServiceContract;
use Rater\Domain\ValueObjects\CellOperators\RatedOperator;
use Rater\Domain\ValueObjects\CellPhoneNumber;

class Decision implements DomainServiceContract
{
    const ACCEPT = true;
    const DECLINE = false;
    const DECISIONS = [
        self::DECLINE => 'Decline',
        self::ACCEPT => 'Accept'
    ];

    private bool $decision;

    /*
    limitItog = k*сума доходу клієнта в національній валюті, де
        k = 0.95 - для клієнтів з мобільним оператором kyivstar
        k = 0.94 - для клієнтів з мобільним оператором vodafone
        k = 0.93 - для клієнтів з мобільним оператором lifeCell
        k = 0.92 - для клієнтів з іншими мобільними операторами

        Якщо limitItog більше 0 - вважати що поле decision = “accept”, інакше “decline”.

    */
    public function __construct(
        float $rate,
        bool $adult,
        float $salary,
        float $requestedLimit
    ) {
        $this->decision = false;

        // Якщо вік клієнта менше 18 років, то requestLimit = 0.
        if (!$adult) $requestedLimit = 0;

        $decisionByRate = $rate * $salary;

        //  Якщо $decisionByRate більше ніж requestLimit,
        if ($decisionByRate >= $requestedLimit)
            // то обмежити значенням requestLimit.
            $decisionByRate = $requestedLimit;

        if ($decisionByRate > 0)
            $this->decision = true;
    }

    public function isPositiveDecision(): bool
    {
        return $this->decision == true;
    }

}