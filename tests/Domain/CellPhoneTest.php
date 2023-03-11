<?php

namespace Tests\Domain;

use Tests\TestCase;
use XCom\CreditRateLimitService\ValueObjects\CellPhoneNumber;

class CellPhoneTest extends TestCase
{
    private CellPhoneNumber $sandbox;
    public function setUp(): void
    {
        parent::setUp();
    }


    public function testValidAsset()
    {
        $this->sandbox = new CellPhoneNumber('+380661234567');

        $this->assertEquals('+380661234567', $this->sandbox->getCellPhoneNumber());
    }

    public function testInvalidAssetLength()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessageMatches('[digit length not equal 13]');

        $this->sandbox = new CellPhoneNumber('+38066123456700000');
    }

    public function testPlusPrefix()
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessageMatches('[should be +]');

        $this->sandbox = new CellPhoneNumber('-380661234567');
    }

    public function testOnlyNumbers()
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessageMatches('[numbers]');

        $this->sandbox = new CellPhoneNumber('+380661234a67');
    }

    public function testCountryCode()
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessageMatches('[country code]');

        $this->sandbox = new CellPhoneNumber('+080661234567');
    }

    public function testMobileOperator()
    {
        $this->expectException(\AssertionError::class);
        $this->expectExceptionMessageMatches('[mobile operator]');

        $this->sandbox = new CellPhoneNumber('+380001234567');
    }
}