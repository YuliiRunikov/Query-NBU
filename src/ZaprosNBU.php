<?php

namespace QueryNBU;

use \GuzzleHttp\Client;

class ZaprosNBU extends EntityAbstract {
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