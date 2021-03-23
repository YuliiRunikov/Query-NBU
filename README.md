# NBU PHP SDK
## Stats

![GitHub repo size](https://img.shields.io/github/repo-size/diynyk/lib-php-nbu-sdk)
[![Build Status](https://travis-ci.com/diynyk/lib-php-nbu-sdk.svg?branch=main)](https://travis-ci.com/diynyk/lib-php-nbu-sdk)
[![codecov](https://codecov.io/gh/diynyk/lib-php-nbu-sdk/branch/main/graph/badge.svg)](https://codecov.io/gh/diynyk/lib-php-nbu-sdk)
![Packagist License](https://img.shields.io/packagist/l/diynyk/nbu-sdk) 
![Packagist Version](https://img.shields.io/packagist/v/diynyk/nbu-sdk)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=diynyk_lib-php-nbu-sdk&metric=alert_status)](https://sonarcloud.io/dashboard?id=diynyk_lib-php-nbu-sdk) 
![Packagist Downloads](https://img.shields.io/packagist/dt/diynyk/nbu-sdk)
![Libraries.io dependency status for latest release](https://img.shields.io/librariesio/release/github/diynyk/lib-php-nbu-sdk)

## Installation
```bash
$ composer require diynyk/nbu-sdk
```

## Usage
```php

$logger = new Psr\Log\NullLogger;

$client = new GuzzleHttp\Client;

$nbuClient = new Diynyk\Nbu\Client($logger, $client);

$rates = $nbuClient->getRates(new DateTime('now'));

echo $rates['USD'];
```
