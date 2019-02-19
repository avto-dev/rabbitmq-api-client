<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use PackageVersions\Versions;
use Tarampampam\Wrappers\Json;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException;

class Client implements ClientInterface
{
    const SELF_PACKAGE_NAME = 'avto-dev/rabbitmq-api-client';

    /**
     * @var ConnectionSettings
     */
    protected $settings;

    /**
     * @var GuzzleClientInterface
     */
    protected $http_client;

    /**
     * Client constructor.
     *
     * @param ConnectionSettings $settings
     * @param array              $guzzle_config
     *
     * @see \GuzzleHttp\Client::__construct
     */
    public function __construct(ConnectionSettings $settings, array $guzzle_config = [])
    {
        $this->settings        = $settings;
        $this->http_client     = $this->httpClientFactory($guzzle_config);
    }

    /**
     * @param bool $without_hash
     *
     * @return string
     */
    public static function clientVersion(bool $without_hash = true): string
    {
        $version = Versions::getVersion(self::SELF_PACKAGE_NAME);

        if ($without_hash === true && \is_int($delimiter_position = \strpos($version, '@'))) {
            return \substr($version, 0, (int) $delimiter_position);
        }

        return $version;
    }

    /**
     * @param string|null $node_name
     *
     * @throws GuzzleException
     * @throws JsonEncodeDecodeException
     *
     * @return bool
     */
    public function healthcheck(string $node_name = null): bool
    {
        $url = '/api/healthchecks/node' . ($node_name === null
                ? ''
                : '/' . \ltrim($node_name, ' /'));

        $response = $this->http_client
            ->request('get', $url, $this->defaultRequestOptions())
            ->getBody()
            ->getContents();

        return (Json::decode($response)['status'] ?? null) === 'ok';
    }

    /**
     * @param string $queue_name
     * @param string $vhost
     *
     * @throws GuzzleException
     * @throws JsonEncodeDecodeException
     *
     * @return QueueInfo
     */
    public function queueInfo(string $queue_name, string $vhost = '/'): QueueInfo
    {
        $url = \sprintf('/api/queues/%s/%s', \urlencode($vhost), \urlencode($queue_name));

        $response = $this->http_client
            ->request('get', $url, $this->defaultRequestOptions())
            ->getBody()
            ->getContents();

        return new QueueInfo(Json::decode($response));
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
