<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{

    public function testGetRates()
    {
        $logger = new Psr\Log\NullLogger;

        $client = new GuzzleHttp\Client;

        $nbuClient = new Diynyk\Nbu\Client($logger, $client);

        $data = $nbuClient->getRates(new DateTime('now'));

        $this->assertArrayHasKey('USD', $data);
    }
/*
    public function testGetRatesBadRequest()
    {
        $logger = new Psr\Log\NullLogger;

        $client = new GuzzleHttp\Client;

        $mock = $this
            ->getMockBuilder('Diynyk\Nbu\Client')
            ->addMethods(['buildUrl'])
            ->setConstructorArgs([$logger, $client])
            ->getMock();

        $mock->expects($this->once())->method('buildUrl')->willReturn('https://google.com/no-file.txt');

        $data = $mock->getRates(new DateTime('now'));

        $this->assertArrayHasKey('USD', $data);

    }
*/

}
