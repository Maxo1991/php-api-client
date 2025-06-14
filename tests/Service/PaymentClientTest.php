<?php

namespace PaymentAPI\Tests\Service;

use PaymentAPI\Exception\InvalidApiKeyException;
use PaymentAPI\Service\PaymentClient;
use PHPUnit\Framework\TestCase;

class PaymentClientTest extends TestCase
{
    public function testGetRateWithValidApiKey()
    {
        $client = new PaymentClient('A123');

        $this->assertEquals(12000, $client->getRate('USD', 100));
        $this->assertEquals(25000, $client->getRate('EUR', 200));
        $this->assertEquals(5600, $client->getRate('JPY', 50));
        $this->assertEquals(118000, $client->getRate('RSD', 1000));
        $this->assertEquals(177000, $client->getRate('RSD', 1500));
        $this->assertEquals(1150, $client->getRate('XYZ', 10));
    }

    public function testGetRateWithLowercaseApiKeyBaseRate()
    {
        $client = new PaymentClient('b999');

        $this->assertEquals(6300, $client->getRate('usd', 100));
        $this->assertEquals(5600, $client->getRate('GBP', 100));
    }

    public function testInvalidApiKeyThrowsException()
    {
        $this->expectException(InvalidApiKeyException::class);

        $client = new PaymentClient('12345');
        $client->getRate('USD', 100);
    }

    public function testEmptyApiKeyThrowsException()
    {
        $this->expectException(InvalidApiKeyException::class);

        $client = new PaymentClient('');
        $client->getRate('USD', 100);
    }

    public function testGetRateWithUnknownCurrency(): void
    {
        $client = new PaymentClient('A123');
        $result = $client->getRate('XYZ', 100);

        $this->assertEquals(11500, $result);
    }
}
