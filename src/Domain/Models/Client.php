<?php declare(strict_types=1);

namespace Rater\Domain\Models;


use DateTime;
use Rater\Contracts\DomainModelContract;
use Rater\Contracts\ValidatableContract;
use Rater\Domain\ValueObjects\Currency;
use Rater\Services\ValidModel\Length;
use Rater\Services\ValidModel\Number;
use Rater\Services\ValidModel\Required;
use Rater\Services\ValidModel\Type;
use Rater\Services\ValidModel\ValidatableTrait;
use Rater\Services\ValidModel\ValidModel;

#[ValidModel]
       # https://www.php.net/manual/en/filter.examples.validation.php
        # https://www.php.net/manual/en/filter.examples.sanitization.php
class Client
implements ValidatableContract, DomainModelContract
{
    use ValidatableTrait; /** useless just showcase  */

    function __construct(
        #[Required]
        private readonly int         $clientId,

        #[Required] /** useless just showcase  */
        #[Length(8)] /** useless just showcase  */
        private readonly \DateTime   $birthday,

        #[Length(15)]
        private readonly string|null $firstname = null,

        #[Length(15)]
        private readonly string|null $lastname = null,


        #[Length(13)]
        private readonly string|null     $phone = null,

        #[Length(25)]
        #[Type(Type::Email)]
        private readonly string|null     $mail = null,

        #[Length(45)]
        private readonly string|null $address = null,

        #[Number]
        private float       $salary = 0.0,

        #[Length(3)]
        private string      $currency = Currency::UAH_ISO,

        # moved to parent aggregate private readonly float         $requestedCreditLimit = 0.0,
        // todo fix it private readonly ?DateTime $created_at,
    ) { }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    public function isAdult(): bool
    {
        $today = (new DateTime())->getTimestamp();

        $adultInMs = (60*60*60*24*356*18);

        if ($this->birthday->getTimestamp() +$adultInMs < $today)
            return true;

        return false;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): void
    {
        $this->salary = $salary;
    }

    public function getCurrency(): Currency|string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
    public function getRequestedCreditLimit(): int
    {
        return $this->requestedCreditLimit;
    }

}
