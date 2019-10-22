<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use Illuminate\Support\Arr;
use AvtoDev\RabbitMqApiClient\ClientInterface;
use Illuminate\Config\Repository as ConfigRepository;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactory;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactoryInterface;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider;

/**
 * @covers \AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider<extended>
 */
class LaravelServiceProviderTest extends AbstractLaravelTestCase
{
    /**
     * @return void
     */
    public function testServiceProviderRegistration(): void
    {
        $this->assertContains(LaravelServiceProvider::class, $this->app->getLoadedProviders());
    }

    /**
     * @return void
     */
    public function testPackageConfigs(): void
    {
        $this->assertFileExists($path = LaravelServiceProvider::getConfigPath());
        $this->assertEquals(LaravelServiceProvider::getConfigRootKeyName(), $base = basename($path, '.php'));

        /** @var ConfigRepository $config */
        $config = $this->app->make(ConfigRepository::class);

        foreach (Arr::dot($configs = require $path) as $key => $value) {
            $this->assertEquals($config->get($base . '.' . $key), $value);
        }

        foreach (['default', 'connections'] as $config_key) {
            $this->assertArrayHasKey($config_key, $configs);
        }

        $default_connection          = $configs['default'];
        $default_connection_settings = $configs['connections'][$default_connection];

        foreach (['entrypoint', 'login', 'password', 'timeout', 'user_agent'] as $connection_key) {
            $this->assertArrayHasKey($connection_key, $default_connection_settings);
        }
    }

    /**
     * @return void
     */
    public function testRegistrationComponentsInIoc(): void
    {
        $this->assertInstanceOf(ClientFactory::class, $this->app->make(ClientFactoryInterface::class));
        $this->assertInstanceOf(ClientInterface::class, $this->app->make(ClientInterface::class));
    }
}
