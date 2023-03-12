<?php declare(strict_types=1);

namespace XCom\CreditRateLimitService\Domain\Models;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\ValidatableContract;
use XCom\CreditRateLimitService\ValueObjects\Uuid;
use XCom\Libraries\ValidModel\Required;
use XCom\Libraries\ValidModel\ValidatableTrait;
use XCom\Libraries\ValidModel\ValidModel;

#[ValidModel]
# https://www.php.net/manual/en/filter.examples.validation.php
# https://www.php.net/manual/en/filter.examples.sanitization.php
class ReviewCreditLimitRequest
    implements ValidatableContract, DomainModelContract
{
    /** not have implementation, just showcase  */
    use ValidatableTrait;

    public function __construct(
        #[Required]            /** not impl, just showcase  */
        private readonly Uuid $uuid,

        #[ValidModel]          // also it #[Required] ? /
        private readonly Client $clientEntity,

        #[Required]
        private readonly float $requestedCreditLimit,
        private readonly float $actualCreditLimit,
        private readonly bool  $resolution,
    ) { }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getClientEntity(): Client
    {
        return $this->clientEntity;
    }

    public function getRequestedCreditLimit(): float
    {
        return $this->requestedCreditLimit;
    }

    public function getActualCreditLimit(): float
    {
        return $this->actualCreditLimit;
    }

    public function isPositiveResolution(): bool
    {
        return $this->resolution;
    }

}
