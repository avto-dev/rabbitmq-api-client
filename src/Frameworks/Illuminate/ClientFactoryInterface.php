<?php

namespace AvtoDev\RabbitMqApiClient\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\ClientInterface;

interface ClientFactoryInterface
{
    /**
     * Get all available connections list.
     *
     * @return array<string>
     */
    public function connectionNames(): array;

    /**
     * Get default connection name.
     *
     * @return string
     */
    public function defaultConnectionName(): string;

    /**
     * Make RabbitMQ API client instance, configured using configuration file (connections section). If connection
     * name is not specified - used default connection name.
     *
     * @param string|null               $connection_name
     * @param array<string, mixed>|null $options
     *
     * @throws \InvalidArgumentException
     *
     * @return ClientInterface
     */
    public function make(string $connection_name = null, array $options = null): ClientInterface;
}
