<?php

namespace XCom\CreditRateLimitService\ValueObjects;

use XCom\CreditRateLimitService\Domain\CellMobileOperators\Kyivstar;
use XCom\CreditRateLimitService\Domain\CellMobileOperators\Lifecell;
use XCom\CreditRateLimitService\Domain\CellMobileOperators\RatedOperator;
use XCom\CreditRateLimitService\Domain\CellMobileOperators\Vodafone;

class CellPhoneNumber
{
    // TODO numbers vs strings
    // Main mind phone is a number not a string
    // buf in some case string more safe, like zero as first char
    // I'm not have research for international cases,
    // so try use string
    const COUNTRY_CODE = [
        'UA' => ['+380']
    ];

    const CELL_OPERATOR_CLASSNAME = [
        Kyivstar::ID => Kyivstar::class,
        Vodafone::ID => Vodafone::class,
        Lifecell::ID => Lifecell::class,
    ];

    const CELL_OPERATORS_CODES = [
        Kyivstar::ID => Kyivstar::CODES,
        Vodafone::ID => Vodafone::CODES,
        Lifecell::ID => Lifecell::CODES,
    ];

    const CELL_OPERATORS_LABELS = [
        Kyivstar::ID => Kyivstar::LABEL,
        Vodafone::ID => Vodafone::LABEL,
        Lifecell::ID => Lifecell::LABEL,
    ];

    private string $countryCode;
    private RatedOperator $operator;
    private string $operatorCode;
    private string $personalNumber;
    private string $cellPhoneNumber;

    /**
     * Full phone number without plus
     * Country + Operator + Personal phone number: 12 digits
     */
    public function __construct(string $value)
    {
        $this->isCellPhoneNumber($value);
        $this->cellPhoneNumber = $value;
    }

    public function getMobileOperator(): RatedOperator
    {
        return $this->operator;
    }

    public function getCellPhoneNumber(): string
    {
        return $this->cellPhoneNumber;
    }

    public function getPersonalNumber(): string
    {
        return $this->personalNumber;
    }

    /**
     * actually this assertion is overcomplicated
     * and can be replaced with simple regexp
     * but otherwise usable as some suggestions for User
     *
     * also parts of cell phone number getters can be published and used everywhere
     * also chunked mobile number can be store in db more effective
     */
    private function isCellPhoneNumber(string $value): void
    {
        assert(strlen($value) == 13,'length not equal 13');

        $_ = str_split($value);

        $plus = join("", array_slice($_, 0, 1));
        assert($plus == '+', 'first symbol should be +');

        $numericPart = join("", array_slice($_, 1));
        assert(is_numeric($numericPart), 'only numbers');

        $this->countryCode = join("", array_slice($_, 0, 4));
        assert($this->isKnownCountryCode(), 'Expect correct country code');

        $this->operatorCode = join("", array_slice($_, 4, 2));
        assert($this->isKnownMobileOperator(), 'Unknown mobile operator');

        $this->personalNumber = join("", array_slice($_, 6, 7));
    }

    private function isKnownMobileOperator(): bool
    {
        foreach (self::CELL_OPERATORS_CODES as $operator => $codes)
            if (in_array($this->operatorCode, $codes ))
            {
                $className = self::CELL_OPERATOR_CLASSNAME[$operator];
                $this->operator = new $className;

                return true;
            }

        return false;
    }

    private function isKnownCountryCode(): bool
    {
        foreach (self::COUNTRY_CODE as $countryISO => $countryMobileCodes)
        {
            if (in_array($this->countryCode, $countryMobileCodes)) return true;
        }

        return false;
    }
}