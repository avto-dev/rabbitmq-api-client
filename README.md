<p align="center">
  <img src="https://hsto.org/webt/59/df/45/59df45aa6c9cb971309988.png" alt="Laravel" width="70" height="70" />
</p>

# RabbitMQ API Client

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Code quality][badge_code_quality]][link_code_quality]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

This package adds into your PHP application RabbitMQ API client implementation.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/rabbitmq-api-client "^1.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

> If your application based on Laravel framework - you can register package service-provider in your `./config/app.php` (Laravel 5.5 and above uses Package Auto-Discovery, so doesn't require you to manually register the service-provider):
>
>```php
>'providers' => [
>    // ...
>    AvtoDev\RabbitMqApiClient\LaravelPackageServiceProvider::class,
>]
>```
>
>If you wants to disable package service-provider auto discover, just add into your `composer.json` next lines:
>
>```json
>{
>    "extra": {
>        "laravel": {
>            "dont-discover": [
>                "avto-dev/rabbitmq-api-client"
>            ]
>        }
>    }
>}
>```
>
> After that you can "publish" package configuration file using following command:
>
> ```php
> $ php artisan vendor:publish --provider="AvtoDev\\RabbitMqApiClient\\Frameworks\\Illuminate\\LaravelServiceProvider"
> ```

## Usage

At first, you should create API client instance:

```php
<?php

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;

$client = new Client(new ConnectionSettings('http://127.0.0.1:15672', 'guest', 'guest'));

// And after that you can execute API commands, for example:

$client::version();     // Client version, like `1.0.0`
$client->healthcheck(); // `true` or `false`
$client->queueInfo('some-queue-name'); // Object with information about queue
```

If you are using Laravel framework with registered package service-provider, you can resolve client instance using DI, for example:

```php
<?php

namespace App\Console\Commands;

use AvtoDev\RabbitMqApiClient\ClientInterface;

class SomeCommand extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'some:command';
    
    /**
     * Execute the console command.
     *
     * @param ClientInterface $client
     *
     * @return void
     */
    public function handle(ClientInterface $client)
    {
        $client->healthcheck(); // `true` or `false`
    }
}
```

### Testing

For package testing we use `phpunit` framework. Just write into your terminal:

```shell
$ git clone git@github.com:avto-dev/rabbitmq-api-client.git ./rabbitmq-api-client && cd $_
$ make test
```

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/rabbitmq-api-client.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/rabbitmq-api-client.svg?longCache=true
[badge_build_status]:https://travis-ci.org/avto-dev/rabbitmq-api-client.svg?branch=master
[badge_code_quality]:https://img.shields.io/scrutinizer/g/avto-dev/rabbitmq-api-client.svg?maxAge=180
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/rabbitmq-api-client/master.svg?maxAge=60
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/rabbitmq-api-client.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/rabbitmq-api-client.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/rabbitmq-api-client.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/rabbitmq-api-client/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/rabbitmq-api-client.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/rabbitmq-api-client.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/rabbitmq-api-client/releases
[link_packagist]:https://packagist.org/packages/avto-dev/rabbitmq-api-client
[link_build_status]:https://travis-ci.org/avto-dev/rabbitmq-api-client
[link_coverage]:https://codecov.io/gh/avto-dev/rabbitmq-api-client/
[link_changes_log]:https://github.com/avto-dev/rabbitmq-api-client/blob/master/CHANGELOG.md
[link_code_quality]:https://scrutinizer-ci.com/g/avto-dev/rabbitmq-api-client/
[link_issues]:https://github.com/avto-dev/rabbitmq-api-client/issues
[link_create_issue]:https://github.com/avto-dev/rabbitmq-api-client/issues/new/choose
[link_commits]:https://github.com/avto-dev/rabbitmq-api-client/commits
[link_pulls]:https://github.com/avto-dev/rabbitmq-api-client/pulls
[link_license]:https://github.com/avto-dev/rabbitmq-api-client/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
