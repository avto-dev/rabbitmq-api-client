<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;
use AvtoDev\RabbitMqApiClient\ConnectionSettingsInterface;
use Illuminate\Support\Str;

/**
 * @coversDefaultClass \AvtoDev\RabbitMqApiClient\ConnectionSettings
 */
class ConnectionSettingsTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementations()
    {
        $this->assertInstanceOf(ConnectionSettingsInterface::class, new ConnectionSettings('http://127.0.0.1:15672'));
    }

    /**
     * @return void
     */
    public function testConstructor()
    {
        $settings = new ConnectionSettings(
            ($point = 'http://rabbitmq.com/foo') . '/ ',
            $name = Str::random(),
            $pass = Str::random(),
            $timeout = \random_int(1, 99),
            $user_agent = Str::random()
        );

        $this->assertSame($point, $settings->getEntryPoint());
        $this->assertSame($name, $settings->getLogin());
        $this->assertSame($pass, $settings->getPassword());
        $this->assertSame($timeout, $settings->getTimeout());
        $this->assertSame($user_agent, $settings->getUserAgent());
    }

    /**
     * @return void
     */
    public function testDefaultUserAgent()
    {
        $settings = new ConnectionSettings('');

        $this->assertSame(\sprintf('%s v%s', Client::SELF_PACKAGE_NAME, Client::version()), $settings->getUserAgent());
    }
}
