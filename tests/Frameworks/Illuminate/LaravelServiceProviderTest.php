<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\Tests\Traits\CreatesApplicationTrait;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider;

class LaravelServiceProviderTest extends BaseTestCase
{
    use CreatesApplicationTrait;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->app->register(LaravelServiceProvider::class);
    }

    /**
     * @return void
     */
    public function testServiceProviderRegistration()
    {
        $this->assertContains(LaravelServiceProvider::class, $this->app->getLoadedProviders());
    }

    /**
     * @return void
     */
    public function testPackageConfigs()
    {
        $this->assertFileExists($path = LaravelServiceProvider::getConfigPath());
        $this->assertEquals(LaravelServiceProvider::getConfigRootKeyName(), $base = basename($path, '.php'));

        /** @var ConfigRepository $config */
        $config = $this->app->make(ConfigRepository::class);

        foreach (array_dot($configs = require $path) as $key => $value) {
            $this->assertEquals($config->get($base . '.' . $key), $value);
        }

        foreach (['foo'] as $config_key) {
            $this->assertArrayHasKey($config_key, $configs);
        }
    }
}
