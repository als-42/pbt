<?php declare(strict_types=1);

namespace Tests\Endpoints;

use Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Tests\TestCase;

final class CreditRateLimitRequestHandlerTest extends TestCase
{
    private Client $httpClient;
    private string $endpoint;
    private Faker\Generator $faker;

    public function testProperRequestAndResponse(): void
    {
        try {
            $response = $this->httpClient->request('POST', $this->endpoint, [
                RequestOptions::JSON => [
                    'clientId' => 1,
                    'firstname' => $this->faker->firstName(),
                    'lastname' => $this->faker->lastName(),
                    'birthday' => (new \DateTime())->format('Y-m-d'),
                    //'phone' => $this->faker->phoneNumber(),
                    'mail' => $this->faker->email(),
                    'address' => $this->faker->address(),
                    'salary' => $this->faker->numberBetween(0, 100000),
                    'currency' => $this->faker->numberBetween(1, 2),
                    'desiredCreditRateLimit' => $this->faker->numberBetween(int2: 4242)
                ]]);

            $responseBody = $response->getBody()->getContents();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            $responseBody;

            $this->assertArrayHasKey('http_status_code', $responseBody);
            $this->assertArrayHasKey('http_status_message', $responseBody);
            $this->assertArrayHasKey('errors', $responseBody);
            $this->assertArrayHasKey('response', $responseBody);

        } catch (GuzzleException $e) { // not often case, but for timeout useful
            $this->logger->critical($e);
            exit($e->getMessage());
        }
    }

    protected function setUp(): void
    {
        $this->httpClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://nginx',
            // You can set any number of default request options.
            'timeout' => 222.0,
            'Accept_' => 'application/json',
            'X-Auth_TODO' => ['Foo', 'Bar', 'Baz']
        ]);
        $this->endpoint = '/creditRateLimit';
        $this->faker = Faker\Factory::create();
    }
}
