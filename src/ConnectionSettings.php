<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

class ConnectionSettings
{
    /**
     * @var string
     */
    protected $entry_point;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected $user_agent;

    /**
     * Connection settings constructor.
     *
     * @param string $entry_point Like `https://rabbitmq.com/` or `http://10.10.10.10:15672/rabbitmq-entrypoint`
     * @param string $login       `guest` by default
     * @param string $password    `guest` by default
     * @param int    $timeout
     * @param string $user_agent
     */
    public function __construct(string $entry_point,
                                string $login = 'guest',
                                string $password = 'guest',
                                int $timeout = 5,
                                string $user_agent = 'Mozilla/5.0 (compatible) RabbitMqApiClient')
    {
        $this->entry_point = \rtrim($entry_point, ' \\/');
        $this->login       = $login;
        $this->password    = $password;
        $this->timeout     = $timeout;
        $this->user_agent  = $user_agent;
    }

    /**
     * @return string
     */
    public function getEntryPoint(): string
    {
        return $this->entry_point;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }
}
