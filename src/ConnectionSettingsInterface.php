<?php

namespace AvtoDev\RabbitMqApiClient;

interface ConnectionSettingsInterface
{
    /**
     * Get RabbitMQ API entrypoint URI.
     *
     * @return string
     */
    public function getEntryPoint(): string;

    /**
     * Get auth login.
     *
     * @return string
     */
    public function getLogin(): string;

    /**
     * Get auth password.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Get requests timeout (in seconds).
     *
     * @return int
     */
    public function getTimeout(): int;

    /**
     * Get user-agent value.
     *
     * @return string
     */
    public function getUserAgent(): string;
}
