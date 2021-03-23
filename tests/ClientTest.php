<?php

use Diynyk\Nbu\Client;
use Diynyk\Nbu\Exceptions\NbuSdkBadBodyException;
use GuzzleHttp\Client as gClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var gClient
     */
    private $client;

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return gClient
     */
    public function getClient(): gClient
    {
        return $this->client;
    }

    /**
     * @param gClient $client
     */
    public function setClient(gClient $client): void
    {
        $this->client = $client;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setClient(new gClient());
        $this->setLogger(new NullLogger());
    }

    /**
     * @return DateTime
     */
    protected function getDate(): DateTime
    {
        return new DateTime('now');
    }

    /**
     * @param string $method
     * @param string $result
     * @return Client
     */
    private function getMockedObj(string $method, string $result): Client
    {
        $mock = $this->getMockBuilder(Client::class)
            ->setConstructorArgs([$this->getLogger(), $this->getClient()])
            ->onlyMethods([$method])
            ->getMock();

        $mock->expects($this->exactly(1))
            ->method($method)
            ->willReturn($result);

        return $mock;
    }

    /**
     * @covers Client::getRates
     * @throws GuzzleException
     */
    public function testGetRates()
    {
        $nbuClient = new Client(
            $this->getLogger(),
            $this->getClient()
        );

        $this->assertArrayHasKey('USD', $nbuClient->getRates($this->getDate()));
    }

    /**
     * @covers Client::getRates
     */
    public function testGetRatesBadRequest()
    {
        // Request SHOULD fail and produce RequestException
        $this->expectException(ClientException::class);


        $this->getMockedObj(
            'buildUrl',
            'https://google.com/no-file.txt'
        )->getRates($this->getDate());
    }

    /**
     * @covers Client::getRates
     */
    public function testGetRatesBadJson()
    {
        // Request SHOULD fail and produce Exception
        $this->expectException(NbuSdkBadBodyException::class);
        $this->expectExceptionMessage('Failed decoding response');


        $this->getMockedObj(
            'extractBody',
            'not json'
        )->getRates($this->getDate());
    }
}
