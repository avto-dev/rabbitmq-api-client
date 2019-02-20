<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ClientInterface;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;
use Illuminate\Config\Repository as ConfigRepository;

class ClientFactory implements ClientFactoryInterface
{
    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var string
     */
    protected $config_root;

    /**
     * Client Factory constructor.
     *
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config      = $config;
        $this->config_root = LaravelServiceProvider::getConfigRootKeyName();
    }

    /**
     * {@inheritdoc}
     */
    public function connectionNames(): array
    {
        return \array_keys($this->config->get("{$this->config_root}.connections"));
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConnectionName(): string
    {
        return (string) $this->config->get("{$this->config_root}.default");
    }

    /**
     * {@inheritdoc}
     */
    public function make(string $connection_name = null): ClientInterface
    {
        $connection_name = $connection_name ?? $this->defaultConnectionName();

        if (! \in_array($connection_name, $this->connectionNames(), true)) {
            throw new \InvalidArgumentException("Connection [$connection_name] does not exists.");
        }

        $connection_settings = $this->config->get("{$this->config_root}.connections.{$connection_name}");

        $settings = new ConnectionSettings(
            (string) $connection_settings['entrypoint'],
            (string) $connection_settings['login'],
            (string) $connection_settings['password'],
            (int) $connection_settings['timeout'],
            (string) $connection_settings['user_agent']
        );

        return $this->clientFactory($settings, (array) ($connection_settings['guzzle_config'] ?? []));
    }

    /**
     * RabbitMQ client factory method.
     *
     * @param mixed ...$arguments
     *
     * @return ClientInterface
     */
    protected function clientFactory(...$arguments): ClientInterface
    {
        return new Client(...$arguments);
    }
}
