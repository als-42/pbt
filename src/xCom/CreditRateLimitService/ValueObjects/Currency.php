<?php declare(strict_types=1);


namespace xCom\CreditRateLimitService\ValueObjects;


class Currency // Aggregate all currencies in one class? not sure
{
    const UAH = 1;
    const EUR = 2;
    const USD = 3;
    const UAH_ISO = 'UAH';
    const EUR_ISO = 'EUR';
    const USD_ISO = 'USD';

    private string $value;
    private array $labels = [
        self::UAH => self::UAH_ISO,
        self::EUR => self::EUR_ISO,
        self::USD => self::USD_ISO,
    ];

    private array $reversedIsoToId = [];

    private float $exchangeRate;

    public function __construct(int|string $value)
    {
        if (is_numeric($value)){
            if (in_array($value, [self::UAH, self::EUR])) $this->value = $value;
        }

        else throw new \DomainException("Missed implementation");
    }

    public static function UAH(): int
    {
        return self::UAH;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getISO(): string
    {
        return $this->labels[$this->value];
    }

}