<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ClientInterface;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;
use Illuminate\Config\Repository as ConfigRepository;
use AvtoDev\RabbitMqApiClient\ConnectionSettingsInterface;

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
     * Create a new ClientFactory instance.
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
        $connection_name = $this->config->get("{$this->config_root}.default");

        return \is_scalar($connection_name)
            ? (string) $connection_name
            : '';
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

        /** @var array<string, mixed> $connection_options */
        $connection_options = $this->config->get("{$this->config_root}.connections.{$connection_name}", []);

        /** @var string $entrypoint */
        $entrypoint = $options[$entrypoint_key = 'entrypoint'] ?? $connection_options[$entrypoint_key];

        /** @var string $login */
        $login = $options[$login_key = 'login'] ?? $connection_options[$login_key];

        /** @var string $password */
        $password = $options[$password_key = 'password'] ?? $connection_options[$password_key];

        $timeout_data = $options[$timeout_key = 'timeout'] ?? $connection_options[$timeout_key] ?? 5;
        $timeout      = \is_numeric($timeout_data) ? \intval($timeout_data) : 5;

        /** @var string|null $user_agent */
        $user_agent = $options[$user_agent_key = 'user_agent'] ?? $connection_options[$user_agent_key] ?? null;

        $settings = new ConnectionSettings($entrypoint, $login, $password, $timeout, $user_agent);

        /** @var array<string, mixed> $guzzle_config */
        $guzzle_config = (array) ($options[$guzzle_config_key = 'guzzle_config'] ?? $connection_options[$guzzle_config_key] ?? []);

        return $this->clientFactory(
            $settings,
            $guzzle_config
        );
    }

    /**
     * @param ConnectionSettingsInterface $settings
     * @param array<string, mixed> $guzzle_config
     *
     * @return ClientInterface
     */
    protected function clientFactory(ConnectionSettingsInterface $settings, array $guzzle_config = []): ClientInterface
    {
        return new Client($settings, $guzzle_config);
    }
}
