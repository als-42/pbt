<?php declare(strict_types=1);

namespace xCom\CreditRateLimitService\Domain\Models;

use xCom\Contracts\DomainModelContract;
use xCom\Contracts\ValidatableContract;
use xCom\CreditRateLimitService\ValueObjects\Uuid;
use xCom\Libraries\ValidModel\Required;
use xCom\Libraries\ValidModel\ValidatableTrait;
use xCom\Libraries\ValidModel\ValidModel;

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
