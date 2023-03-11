<?php declare(strict_types=1);

namespace XCom\CreditRateLimitService\Domain\Models;

use XCom\Contracts\DomainModelContract;
use XCom\Contracts\ValidatableContract;
use XCom\CreditRateLimitService\ValueObjects\Uuid;
use XCom\Libraries\ValidModel\Required;
use XCom\Libraries\ValidModel\ValidatableTrait;
use XCom\Libraries\ValidModel\ValidModel;

#[ValidModel]
class ClientRequest
implements ValidatableContract, DomainModelContract
{
    use ValidatableTrait; /** not impl, just showcase  */


    public function __construct(
        #[Required] /** not impl, just showcase  */
        private readonly Uuid         $uuid,

        #[ValidModel] // also it #[Required] ? /
        # https://www.php.net/manual/en/filter.examples.validation.php
        # https://www.php.net/manual/en/filter.examples.sanitization.php
        private readonly Client $clientEntity,

        #[Required]
        private readonly float        $requestedCreditLimit,
        private readonly bool $decision = false,
    ) {
    }

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

    public function isDecision(): bool
    {
        return $this->decision;
    }

}
