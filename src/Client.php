<?php

namespace Diynyk\Nbu;

use GuzzleHttp\Client as gClient;
use Psr\Log\LoggerInterface;

class Client {

  private LoggerInterface $log;

  private gClient $client;

  public function __construct(LoggerInterface $logger, gClient $client)
  {
    $this->log = $logger;
    $this->client = $client;
  } 

  const TEMPLATE = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&date=%s';
  const DATE_FORMAT = 'Ymd'; 
  
  private function getData(DateTime $date) {
    $url = vsprintf(self::TEMPLATE, [$date->format(self::DATE_FORMAT)]);
    $response = $this->client->request('GET', $url);
  }

  public function transformResponse($data) {
    return array_column($data, 'rate', 'cc');
  }

  public function getRates(DateTime $date) {
    $nbuResponse = $this->getData($date);
    
    if ($nbuResponse->getStatusCode() >= 400 ) {
      die('error');
    }
    $data = json_decode($nbuResponse->getBody(), true);
    return  $this->transformResponse($data);
  }
}
