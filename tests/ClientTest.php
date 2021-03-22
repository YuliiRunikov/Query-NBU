<?php

use GuzzleHttp\Client;
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
     * @var Client
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
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setClient(new Client());
        $this->setLogger(new NullLogger());
    }

    public function testGetRates()
    {
        $nbuClient = new Diynyk\Nbu\Client(
            $this->getLogger(),
            $this->getClient()
        );

        $data = $nbuClient->getRates(new DateTime('now'));

        $this->assertArrayHasKey('USD', $data);
    }

    public function testGetRatesBadRequest()
    {
        // Request SHOULD fail and produce RequestException
        $this->expectException(GuzzleHttp\Exception\RequestException::class);

        $date = new DateTime('now');

        $mock = $this->getMockBuilder(\Diynyk\Nbu\Client::class)
            ->setConstructorArgs([$this->getLogger(), $this->getClient()])
            ->onlyMethods(['buildUrl'])
            ->getMock();

        $mock->expects($this->exactly(1))
            ->method('buildUrl')
            ->willReturn('https://google.com/no-file.txt'); // <-- not exists, force 4XX error

        $mock->getRates($date);
    }

    public function testGetRatesBadJson()
    {
        // Request SHOULD fail and produce Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed decoding response');

        $date = new DateTime('now');

        $mock = $this->getMockBuilder(\Diynyk\Nbu\Client::class)
            ->setConstructorArgs([$this->getLogger(), $this->getClient()])
            ->onlyMethods(['extractBody'])
            ->getMock();

        $mock->expects($this->exactly(1))
            ->method('extractBody')
            ->willReturn('not json'); // <-- bad JSON

        $mock->getRates($date);
    }
}
