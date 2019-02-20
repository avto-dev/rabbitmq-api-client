<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleHttpClient;
use AvtoDev\RabbitMqApiClient\ClientInterface;
use AvtoDev\RabbitMqApiClient\ConnectionSettingsInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactory;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactoryInterface;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider;

/**
 * @coversDefaultClass \AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactory
 */
class ClientFactoryTest extends AbstractLaravelTestCase
{
    /**
     * @var ClientFactory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $root;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->factory = new ClientFactory($this->app->make(ConfigRepository::class));
        $this->root    = LaravelServiceProvider::getConfigRootKeyName();
    }

    /**
     * @return void
     */
    public function testImplementation()
    {
        $this->assertInstanceOf(ClientFactoryInterface::class, $this->factory);
    }

    /**
     * @return void
     */
    public function testConnectionNames()
    {
        $this->assertSame(
            \array_keys($this->app->make(ConfigRepository::class)->get("{$this->root}.connections")),
            $this->factory->connectionNames()
        );
    }

    /**
     * @return void
     */
    public function testDefaultConnectionName()
    {
        $this->assertSame(
            $this->app->make(ConfigRepository::class)->get("{$this->root}.default"),
            $this->factory->defaultConnectionName()
        );
    }

    /**
     * @return void
     */
    public function testMakeThrownAnExceptionThenPassedWrongConnectionName()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->make(Str::random());
    }

    /**
     * @return void
     */
    public function testMakeWithoutPassingConnectionName()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->factory->make());
    }

    /**
     * @return void
     */
    public function testMakeWithPassingConnectionName()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->factory->make('default-connection'));
    }

    /**
     * @return void
     */
    public function testMakeWithPassingOptions()
    {
        $client = $this->factory->make(null, [
            'entrypoint'    => $entrypoint = 'https://foo/bar',
            'login'         => $login = 'login',
            'password'      => $password = 'password',
            'timeout'       => $timeout = \random_int(1, 99),
            'user_agent'    => $user_agent = Str::random(),
            'guzzle_config' => $guzzle_config = [
                'base_uri' => $base_uri = 'http://httpbin.org',
            ],
        ]);

        $reflection = new \ReflectionClass($client);

        $http_client = $reflection->getProperty('http_client');
        $http_client->setAccessible(true);
        $http_client = $http_client->getValue($client);
        /** @var GuzzleHttpClient $http_client */
        $settings = $reflection->getProperty('settings');
        $settings->setAccessible(true);
        $settings = $settings->getValue($client);
        /* @var ConnectionSettingsInterface $settings */

        $this->assertSame($entrypoint, $settings->getEntryPoint());
        $this->assertSame($login, $settings->getLogin());
        $this->assertSame($password, $settings->getPassword());
        $this->assertSame($timeout, $settings->getTimeout());
        $this->assertSame($user_agent, $settings->getUserAgent());

        $http_client_reflection = new \ReflectionClass($http_client);

        $http_config = $http_client_reflection->getProperty('config');
        $http_config->setAccessible(true);
        $http_config = $http_config->getValue($http_client);
        /* @var array $http_config */

        $this->assertSame($base_uri, (string) $http_config['base_uri']);
    }
}
