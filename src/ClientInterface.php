<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use GuzzleHttp\Exception\RequestException;
use Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException;

interface ClientInterface
{
    /**
     * Get current client version.
     *
     * @param bool $without_hash
     *
     * @return string
     */
    public static function version(bool $without_hash = true): string;

    /**
     * Call to the healthcheck method (returns true only id response contains success status).
     *
     * @param string|null $node_name
     *
     * @throws RequestException
     * @throws JsonEncodeDecodeException
     *
     * @return bool
     */
    public function healthcheck(string $node_name = null): bool;

    /**
     * Get queue info by name (and vhost).
     *
     * @param string $queue_name
     * @param string $vhost
     *
     * @throws RequestException
     * @throws JsonEncodeDecodeException
     *
     * @return QueueInfo
     */
    public function queueInfo(string $queue_name, string $vhost = '/'): QueueInfoInterface;
}
