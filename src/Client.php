<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use PackageVersions\Versions;
use Tarampampam\Wrappers\Json;

class Client implements ClientInterface
{
    /**
     * Self package name.
     */
    const SELF_PACKAGE_NAME = 'avto-dev/rabbitmq-api-client';

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
     * @param array              $guzzle_config
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

        return (Json::decode($contents)['status'] ?? null) === 'ok';
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

        return new QueueInfo((array) Json::decode($contents));
    }

    /**
     * HTTP client factory.
     *
     * @param mixed ...$arguments
     *
     * @return GuzzleClientInterface
     */
    protected function httpClientFactory(...$arguments): GuzzleClientInterface
    {
        return new GuzzleHttpClient(...$arguments);
    }

    /**
     * Default HTTP request options (should be compatible with Guzzle options set).
     *
     * @link <http://docs.guzzlephp.org/en/stable/request-options.html>
     *
     * @return array
     */
    protected function defaultRequestOptions(): array
    {
        return [
            'auth'     => [$this->settings->getLogin(), $this->settings->getPassword()],
            'base_uri' => $this->settings->getEntryPoint(),
            'timeout'  => $this->settings->getTimeout(),
            'headers'  => [
                'User-Agent' => $this->settings->getUserAgent(),
                'Accept'     => 'application/json',
            ],
        ];
    }
}
