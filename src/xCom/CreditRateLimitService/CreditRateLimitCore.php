<?php declare(strict_types=1);

namespace xCom\CreditRateLimitService;

use xCom\Contracts\DomainServiceContract;
use xCom\CreditRateLimitService\Domain\Decision;
use xCom\CreditRateLimitService\Domain\Models\Client;
use xCom\CreditRateLimitService\Domain\Models\ClientRequest;
use xCom\CreditRateLimitService\Infrastructure\CurrencyExchangeService;
use xCom\CreditRateLimitService\ValueObjects\CellPhoneNumber;
use xCom\CreditRateLimitService\ValueObjects\Currency;

class CreditRateLimitCore implements DomainServiceContract
{

    public function __construct(
        private readonly CurrencyExchangeService $currencyExchangeService,
    ) {
        // it is first version of service
        // w.o. retrospective in production use
        // w.o. ability to tests and mock
        // just an example for how im think and how can solve this test case
        // ps: last update move from domain service to app level because:
        // (has deps - currency exchange service, and app logic data flow behaviours)
        // ver: 0.0.2:
        // - move persistence duties to ClientRequestRepository
        // - add ability for testing the resolveCreditRateLimitDecision method
        // - at all for this service fixed single-responsibility
        // - clean up thinks in method names
    }

    // Розрахувати limitItog (кредитний ліміт, який ми можемо надати) по формулі:
    public function resolveCreditRateLimitDecision(ClientRequest $clientRequest): ClientRequest
    {
        return new ClientRequest(
            $clientRequest->getUuid(),
            $this->exchangeClientSalaryToUAH($clientRequest->getClientEntity()),
            $clientRequest->getRequestedCreditLimit(),
            ($this->newDecision($clientRequest))->resolution()
        );
    }

    private function newDecision(ClientRequest $clientRequest): Decision
    {
        $cellPhone = (new CellPhoneNumber(
            $clientRequest->getClientEntity()->getPhone()
        ));

        return new Decision(
            ($cellPhone->getMobileOperator())->getRate(),
            $clientRequest->getClientEntity()->isAdult(),
            $clientRequest->getClientEntity()->getSalary(),
            $clientRequest->getRequestedCreditLimit()
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