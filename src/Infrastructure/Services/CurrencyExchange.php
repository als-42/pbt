<?php declare(strict_types=1);

namespace Rater\Infrastructure\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class CurrencyExchange
{

    public function __construct(
        private readonly LoggerInterface $logger
    ) { }

    public function exchange(string $currency, float $sum): float
    {
        try {
            $url = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
            $response = json_decode((new Client())->request('GET', $url)->getBody()->getContents());
            // [{"ccy":"EUR","base_ccy":"UAH","buy":"40.00000","sale":"41.00000"},{"ccy":"USD","base_ccy":"UAH","buy":"38.40000","sale":"38.90000"}]
            // sure we have Currency class
            // and nice to have implement same cache system maybe memcahche
            //$cache = new \Memcache();
            //$cache->connect()
            foreach ($response as $exchangeRate)
            {
                // {"ccy":"EUR","base_ccy":"UAH","buy":"40.00000","sale":"41.00000"}
                $exchangeRate;

                if ($currency == $exchangeRate->ccy){
                    return $sum / $exchangeRate->sale;
                }
            }
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage(), [self::class]);
        }

        return -1;
    }
}