<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use PackageVersions\Versions;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\RequestOptions as GuzzleOptions;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

class Client implements ClientInterface
{
    /**
     * Self package name.
     */
    public const SELF_PACKAGE_NAME = 'avto-dev/rabbitmq-api-client';

    /**
     * @var ConnectionSettingsInterface
     */
    protected $settings;

    /**
     * @var GuzzleClientInterface
     */
    protected $http_client;

    /**
     * Client constructor.
     *
     * @param ConnectionSettingsInterface $settings
     * @param array<string, mixed>        $guzzle_config
     *
     * @see \GuzzleHttp\Client::__construct
     */
    public function __construct(ConnectionSettingsInterface $settings, array $guzzle_config = [])
    {
        $this->settings    = $settings;
        $this->http_client = $this->httpClientFactory($guzzle_config);
    }

    /**
     * {@inheritdoc}
     */
    public static function version(bool $without_hash = true): string
    {
        $version = Versions::getVersion(self::SELF_PACKAGE_NAME);

        if ($without_hash === true && \is_int($delimiter_position = \mb_strpos($version, '@'))) {
            return \mb_substr($version, 0, (int) $delimiter_position);
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function healthcheck(string $node_name = null): bool
    {
        $url = '/api/healthchecks/node' . ($node_name === null
                ? ''
                : '/' . \ltrim($node_name, ' /'));

        $contents = $this->http_client
            ->request('get', $url, $this->defaultRequestOptions())
            ->getBody()
            ->getContents();

        $contents = (array) \json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        return ($contents['status'] ?? null) === 'ok';
    }

    /**
     * {@inheritdoc}
     */
    public function queueInfo(string $queue_name, string $vhost = '/'): QueueInfoInterface
    {
        $url = \sprintf('/api/queues/%s/%s', \urlencode($vhost), \urlencode($queue_name));

        $contents = $this->http_client
            ->request('get', $url, $this->defaultRequestOptions())
            ->getBody()
            ->getContents();

        return new QueueInfo((array) \json_decode($contents, true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * HTTP client factory.
     *
     * @param array<string, mixed> $arguments
     *
     * @return GuzzleClientInterface
     */
    protected function httpClientFactory(array $arguments): GuzzleClientInterface
    {
        return new GuzzleHttpClient($arguments);
    }

    /**
     * Default HTTP request options (should be compatible with Guzzle options set).
     *
     * @link <http://docs.guzzlephp.org/en/stable/request-options.html>
     *
     * @return array<string, mixed>
     */
    protected function defaultRequestOptions(): array
    {
        return [
            'base_uri'             => $this->settings->getEntryPoint(),
            GuzzleOptions::AUTH    => [$this->settings->getLogin(), $this->settings->getPassword()],
            GuzzleOptions::TIMEOUT => $this->settings->getTimeout(),
            GuzzleOptions::HEADERS => [
                'User-Agent' => $this->settings->getUserAgent(),
                'Accept'     => 'application/json',
            ],
        ];
    }
}
