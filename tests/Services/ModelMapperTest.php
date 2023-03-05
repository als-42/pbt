<?php

namespace Tests\Services;

use Faker;
use Faker\Provider\Uuid;
use Rater\Domain\Models\ClientRequest;
use Rater\Services\ModelMapper;
use Tests\TestCase;

class ModelMapperTest extends TestCase
{
    private Faker\Generator $faker;

    public function testMap()
    {
        $payload = [
            'uuid' => Uuid::uuid(),
            'clientId' => 1,
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'birthday' => (new \DateTime())->format('Y-m-d'),
            //'phone' => $this->faker->phoneNumber(),
            'mail' => $this->faker->email(),
            'address' => $this->faker->address(),
            'salary' => $this->faker->numberBetween(0, 100000),
            'currency' => $this->faker->numberBetween(1, 2),
            'requestedCreditLimit' => $this->faker->randomFloat( 4242)
        ];

        $obj = json_decode(json_encode($payload));

        $tmp = ModelMapper::Map($obj, ClientRequest::class);

        $tmp;
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->faker = Faker\Factory::create();
    }
}