<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use Illuminate\Support\Str;
use AvtoDev\RabbitMqApiClient\ClientInterface;
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
}
