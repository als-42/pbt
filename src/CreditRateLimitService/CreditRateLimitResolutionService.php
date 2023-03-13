<?php declare(strict_types=1);

namespace XCom\CreditRateLimitService;

use XCom\Contracts\ApplicationServiceContract;
use XCom\CreditRateLimitService\Domain\Decision;
use XCom\CreditRateLimitService\Domain\Models\Client;
use XCom\CreditRateLimitService\Domain\Models\ReviewCreditLimitRequest;
use XCom\CreditRateLimitService\Infrastructure\CurrencyExchangeService;
use XCom\CreditRateLimitService\ValueObjects\CellPhoneNumber;
use XCom\CreditRateLimitService\ValueObjects\Currency;

class CreditRateLimitResolutionService
    implements ApplicationServiceContract /* ?? */
{

    public function __construct(
        private readonly CurrencyExchangeService $currencyExchangeService,
    ) {
    }

    // Розрахувати limitItog (кредитний ліміт, який ми можемо надати) по формулі:
    public function resolveNewCreditRateLimit(ReviewCreditLimitRequest $clientRequest): ReviewCreditLimitRequest
    {
        $cellPhone = (new CellPhoneNumber(
            $clientRequest->getClient()->getPhone()
        ));

        $decision = new Decision(
            ($cellPhone->getMobileOperator())->getRate(),
            $clientRequest->getClient()->isAdult(),
            $clientRequest->getClient()->getSalary(),
            $clientRequest->getRequestedCreditLimit()
        );

        return new ReviewCreditLimitRequest(
            $clientRequest->getUuid(),
            $this->exchangeClientSalaryToUAH($clientRequest->getClient()),
            $clientRequest->getRequestedCreditLimit(),
            $decision->getActualCreditLimit(),
            $decision->getResolution()
        );
    }

    private function exchangeClientSalaryToUAH(Client $client): Client
    {
        // Сконвертувати суму доходу (monthSalary) в національну валюту,
        // викликавши будь-яке публічно доступне апі з курсом валют
        // (наприклад https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5)

        $currency = new Currency($client->getCurrency());

        if ($currency != Currency::UAH_ISO) {
            $salary = $client->getSalary();

            $salary = $this->currencyExchangeService->exchange($currency->getISO(), $salary);
            assert($salary > 0, 'should be positive, looks like an error with exchange service');

            $client->setSalary($salary);
            $client->setCurrency(Currency::UAH_ISO);

            return $client;
        }

        return $client;
    }
}