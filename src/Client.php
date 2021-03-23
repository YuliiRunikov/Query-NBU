<?php

namespace Diynyk\Nbu;

use DateTime;
use Diynyk\Nbu\Exceptions\NbuSdkBadBodyException;
use Diynyk\Nbu\Exceptions\NbuSdkBadResponseException;
use GuzzleHttp\Client as gClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

class Client
{
    const TEMPLATE = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&date=%s';
    const DATE_FORMAT = 'Ymd';

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var gClient
     */
    private $client;

    /**
     * Client constructor.
     * @param LoggerInterface $logger
     * @param gClient $client
     */
    public function __construct(LoggerInterface $logger, gClient $client)
    {
        $this->log = $logger;
        $this->client = $client;
    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function buildUrl(DateTime $date): string
    {
        $url = vsprintf(self::TEMPLATE, [$date->format(self::DATE_FORMAT)]);
        $this->log->debug(vsprintf('Using request url=%s', [$url]));
        return $url;
    }

    private function getClientOptions(): array
    {
        return [
            RequestOptions::HTTP_ERRORS => false,
        ];
    }

    /**
     * @param DateTime $date
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function getData(DateTime $date): ResponseInterface
    {
        return $this->client->request('GET', $this->buildUrl($date), $this->getClientOptions());
    }

    /**
     * @param array $data
     * @return array
     */
    private function transformResponse(array $data): array
    {
        return array_column($data, 'rate', 'cc');
    }

    /**
     * @param ResponseInterface $response
     * @return StreamInterface
     */
    protected function extractBody(ResponseInterface $response): string
    {
        return (string)$response->getBody();
    }

    /**
     * @param DateTime $date
     * @return array
     * @throws GuzzleException
     */
    public function getRates(DateTime $date): array
    {
        $nbuResponse = $this->getData($date);

        $body = $this->extractBody($nbuResponse);

        if ($nbuResponse->getStatusCode() >= 400) {
            $this->log->error(vsprintf('Got bad request response: %s', [$body]));
            throw new NbuSdkBadResponseException('Got Bad Response');
        }

        $this->log->debug(vsprintf('Got response body: %s', [$body]));
        $data = json_decode($body, true);

        if (is_null($data) || empty($data)) {
            $this->log->error(vsprintf('Failed decoding response: %s', [$body]));
            throw new NbuSdkBadBodyException('Failed decoding response');
        }

        return $this->transformResponse($data);
    }
}
