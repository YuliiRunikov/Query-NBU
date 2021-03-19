<?php

namespace QueryNBU;

use \GuzzleHttp\Client;

class ZaprosNBU {

  protected $log;

  protected $client;

  protected $entity = '';

  public function __construct($entity, $logger, $client)
  {
    $this->entity = $entity;
    $this->log=$logger;
    $this->client=$client;
  } 

  const TEMPLATE = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew?json&date=%s';
  const DATE_FORMAT = 'Ymd'; 
  

  public function getRates() {
    $url =    
    vsprintf(
        self::TEMPLATE,
        [
         date (self::DATE_FORMAT)
        ]
      );
     // die ($url);          
    
    $response = $this->client->request(
      'GET',
      $url);

    if ($response->getStatusCode() >= 400 ) {
      die('error');
    }
    
    $data = json_decode($response->getBody(), true);
    
    $rates= array_column($data, 'rate', 'cc');
    return  $rates;

  }
}


  
