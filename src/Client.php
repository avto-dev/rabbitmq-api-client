<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use Tarampampam\Wrappers\Json;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException;

class Client implements ClientInterface
{
    /**
     * @var ConnectionSettings
     */
    protected $settings;

    /**
     * @var GuzzleClientInterface
     */
    protected $http_client;

    /**
     * @var array
     */
    protected $request_options = [];

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
        $this->request_options = $this->defaultRequestOptions();
    }

    /**
     * @param string|null $node_name
     *
     * @return bool
     *
     * @throws GuzzleException
     * @throws JsonEncodeDecodeException
     */
    public function healthcheck(string $node_name = null): bool
    {
        $url = '/api/healthchecks/node' . ($node_name === null
                ? ''
                : '/' . \ltrim($node_name, ' /'));

        $response = $this->http_client
            ->request('get', $url, $this->request_options)
            ->getBody()
            ->getContents();

        return (Json::decode($response)['status'] ?? null) === 'ok';
    }

    /**
     * @param string $queue_name
     * @param string $vhost
     *
     * @return QueueInfo
     *
     * @throws GuzzleException
     * @throws JsonEncodeDecodeException
     */
    public function queueInfo(string $queue_name, string $vhost = '/'): QueueInfo
    {
        $url = \sprintf('/api/queues/%s/%s', \urlencode($vhost), \urlencode($queue_name));

        $response = $this->http_client
            ->request('get', $url, $this->request_options)
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
