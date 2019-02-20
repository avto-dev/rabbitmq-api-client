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
        return \array_keys((array) $this->config->get("{$this->config_root}.connections"));
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
    public function make(string $connection_name = null, array $options = null): ClientInterface
    {
        $connection_name = $connection_name ?? $this->defaultConnectionName();

        if (! \in_array($connection_name, $this->connectionNames(), true)) {
            throw new \InvalidArgumentException("Connection [$connection_name] does not exists.");
        }

        $connection_settings = $this->config->get("{$this->config_root}.connections.{$connection_name}");

        $settings = new ConnectionSettings(
            (string) ($options[$entrypoint_key = 'entrypoint'] ?? $connection_settings[$entrypoint_key]),
            (string) ($options[$login_key = 'login'] ?? $connection_settings[$login_key]),
            (string) ($options[$password_key = 'password'] ?? $connection_settings[$password_key]),
            (int) ($options[$timeout_key = 'timeout'] ?? $connection_settings[$timeout_key] ?? 5),
            $options[$user_agent_key = 'user_agent'] ?? $connection_settings[$user_agent_key] ?? null
        );

        return $this->clientFactory(
            $settings,
            (array) ($options[$guzzle_config_key = 'guzzle_config'] ?? $connection_settings[$guzzle_config_key] ?? [])
        );
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
