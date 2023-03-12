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
class ReviewCreditLimitRequest // it is aggregate or model????
    // used as aggregate, but actually is a model!
    implements ValidatableContract, DomainModelContract
{
    /** not have implementation, just showcase  */
    use ValidatableTrait;

    /**
     * todo: add Value Object will be better
     *
     * VO allow delegate validation to independent classes
     * because domain model looks like a data validation controller
     */
    public function __construct(
        #[Required]
        private readonly Uuid $uuid,

        #[ValidModel]
        // also it #[Required] ? /
        private readonly Client $client, /** NOT see any reason for embed client here */

        #[Required]
        private readonly float $requestedCreditLimit,
        private readonly float $actualCreditLimit,
        private readonly bool  $resolution,
    ) { }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getClient(): Client
    {
        return $this->client;
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
