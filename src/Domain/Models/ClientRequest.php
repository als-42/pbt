<?php declare(strict_types=1);

namespace Rater\Domain\Models;

use Rater\Contracts\DomainModelContract;
use Rater\Contracts\ValidatableContract;
use Rater\Domain\ValueObjects\Uuid;
use Rater\Services\ValidModel\Required;
use Rater\Services\ValidModel\ValidatableTrait;
use Rater\Services\ValidModel\ValidModel;

#[ValidModel]
class ClientRequest
implements ValidatableContract, DomainModelContract
{
    use ValidatableTrait; /** not impl, just showcase  */


    public function __construct(
        #[Required] /** not impl, just showcase  */
        private readonly Uuid         $uuid,

        #[ValidModel] // also it #[Required] ? /

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