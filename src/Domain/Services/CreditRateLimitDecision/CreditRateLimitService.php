<?php declare(strict_types=1);

namespace Rater\Domain\Services\CreditRateLimitDecision;

use Rater\Contracts\DomainServiceContract;
use Rater\Domain\Models\ClientRequest;
use Rater\Domain\ValueObjects\CellPhoneNumber;
use Rater\Domain\ValueObjects\Currency;
use Rater\Infrastructure\Persistence\ClientsRequestsStoreCommandsHandler;
use Rater\Infrastructure\Persistence\ClientsStoreCommandsHandler;
use Rater\Infrastructure\Persistence\ClientsStoreQueriesHandler;
use Rater\Infrastructure\Services\CurrencyExchange;

class CreditRateLimitService implements DomainServiceContract
{
    private  ClientRequest $clientRequest;

    public function __construct(
        private readonly CurrencyExchange                    $currencyExchangeService,
        private readonly ClientsStoreQueriesHandler          $clientsStoreQueryHandler,
        private readonly ClientsStoreCommandsHandler         $clientsStoreCommandsHandler,
        private readonly ClientsRequestsStoreCommandsHandler $clientsRequestsStoreCommands,
    ) {
        // it is first version of service
        // w.o. retrospective in production use
        // w.o. ability to tests and mock
        // just an example for how im think and how can solve this test case
    }

    // Розрахувати limitItog (кредитний ліміт, який ми можемо надати) по формулі:
    public function solveCreditRateLimitDecision(ClientRequest $clientRequest)
    {
        $this->clientRequest = $clientRequest;

        $this->checkCurrencyExchange();

        if (($this->newDecision())->isPositiveDecision()) {
            $this->clientRequest = new ClientRequest(
                $this->clientRequest->getUuid(),
                $this->clientRequest->getClientEntity(),
                $this->clientRequest->getRequestedCreditLimit(),
                true
            );
        }

        $this->persistDecision();
    }

    private function newDecision(): Decision
    {
        $mobileOperator = (new CellPhoneNumber(
            $this->clientRequest->getClientEntity()->getPhone()
        ))->getMobileOperator();

        return new Decision(
            $mobileOperator->getRate(),
            $this->clientRequest->getClientEntity()->isAdult(),
            $this->clientRequest->getClientEntity()->getSalary(),
            $this->clientRequest->getRequestedCreditLimit()
        );
    }

    private function checkCurrencyExchange()
    {
        // Сконвертувати суму доходу (monthSalary) в національну валюту,
        // викликавши будь-яке публічно доступне апі з курсом валют
        // (наприклад https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5)

        $currency = new Currency($this->clientRequest->getClientEntity()->getCurrency());

        if ($currency != Currency::UAH_ISO) {
            $salary = $this->clientRequest->getClientEntity()->getSalary();

            $salary = $this->currencyExchangeService->exchange($currency->getISO(), $salary);
            assert($salary > 0, 'should be positive, looks like an error with exchange service');

            $client = $this->clientRequest->getClientEntity();
            // todo need useful mapper for ability of immutable obj
            // for now we update object with setter
            $client->setSalary($salary);
            $client->setCurrency(Currency::UAH_ISO);

            $this->clientRequest = new ClientRequest(
                $this->clientRequest->getUuid(),
                $client,
                $this->clientRequest->getRequestedCreditLimit()
            );
        }
    }

    // Виконати запис параметрів до БД в таблицю Client з рішенням по кредиту.
    private function persistDecision()
    {
        // we not handle, for clients salary history

        $existsClient = $this->clientsStoreQueryHandler
            ->readById($this->clientRequest->getClientEntity()->getClientId());

        if (!$existsClient)
            $this->clientsStoreCommandsHandler
                ->insert($this->clientRequest->getClientEntity());

        $this->clientsRequestsStoreCommands
            ->insert($this->clientRequest);
    }


}