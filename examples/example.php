<?php

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new Psr\Log\NullLogger;

$clinet = new GuzzleHttp\Client;

$nbuClient = new Diynyk\Nbu\Client($logger, $client);

var_export($nbuClient->getRates(new DateTime('now'));
