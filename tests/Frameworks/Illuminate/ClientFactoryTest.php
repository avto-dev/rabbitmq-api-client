<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\ClientInterface;
use AvtoDev\RabbitMqApiClient\ConnectionSettingsInterface;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactory;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\ClientFactoryInterface;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Str;

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
     * @var ConfigRepository
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->root   = LaravelServiceProvider::getConfigRootKeyName();
        $this->config = $this->app->make(ConfigRepository::class);

        $this->config->set("{$this->root}.connections.test", [
            'entrypoint' => 'http://127.0.0.1:15672',
            'login'      => 'guest',
            'password'   => 'guest',
            'timeout'    => 25,
            'user_agent' => 'Awesome User-Agent',
        ]);
        $this->config->set("{$this->root}.default", 'test');

        $this->factory = new ClientFactory($this->config);
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
            \array_keys($this->config->get("{$this->root}.connections")),
            $this->factory->connectionNames()
        );
    }

    /**
     * @return void
     */
    public function testDefaultConnectionName()
    {
        $this->assertSame(
            $this->config->get("{$this->root}.default"),
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
        $this->assertInstanceOf(ClientInterface::class, $this->factory->make('test'));
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
            'guzzle_config' => [
                'base_uri' => $base_uri = 'http://httpbin.org',
            ],
        ]);

        /** @var GuzzleHttpClient $http_client */
        $http_client = $this->getProperty($client, 'http_client');
        /* @var ConnectionSettingsInterface $settings */
        $settings = $this->getProperty($client, 'settings');

        $this->assertSame($entrypoint, $settings->getEntryPoint());
        $this->assertSame($login, $settings->getLogin());
        $this->assertSame($password, $settings->getPassword());
        $this->assertSame($timeout, $settings->getTimeout());
        $this->assertSame($user_agent, $settings->getUserAgent());

        /* @var array $http_config */
        $http_config = $this->getProperty($http_client, 'config');

        $this->assertSame($base_uri, (string) $http_config['base_uri']);
    }

    /**
     * @return void
     */
    public function testMakeWithoutPassingOptions()
    {
        /** @var array $options */
        $options = $this->config->get("{$this->root}.connections.test");
        $client  = $this->factory->make();

        /* @var ConnectionSettingsInterface $settings */
        $settings = $this->getProperty($client, 'settings');

        $this->assertSame($options['entrypoint'], $settings->getEntryPoint());
        $this->assertSame($options['login'], $settings->getLogin());
        $this->assertSame($options['password'], $settings->getPassword());
        $this->assertSame($options['timeout'], $settings->getTimeout());
        $this->assertSame($options['user_agent'], $settings->getUserAgent());
    }
}
