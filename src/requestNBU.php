<?php

namespace diynyk\nbu\sdk;

use \GuzzleHttp\Client;

class requestNBU {

  protected $log;

  protected $client;

  
  public function __construct( $logger, $client)
  {
    $this->log=$logger;
    $this->client=$client;
  } 

  const TEMPLATE = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&date=%s';
  const DATE_FORMAT = 'Ymd'; 
  


  private function getData() {

    $url =    
    vsprintf(
        self::TEMPLATE,
        [
         date (self::DATE_FORMAT)
        ]
      );

  }

  public function transformResponse(){

    $response = $this->client->request(
      'GET',
      $this->getData);

    if ($response->getStatusCode() >= 400 ) {
      die('error');

      $data = json_decode($response->getBody(), true);
    }
  }

  public function getRates(){

    $callgetData= $this->getData;

    $rates= array_column($this->transformResponse, 'rate', 'cc');
    return  $rates;

  }
}
