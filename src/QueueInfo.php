<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

/**
 * @todo: Write all data getters
 */
class QueueInfo
{
    /**
     * @var array
     */
    protected $raw_data;

    /**
     * Queue Info constructor.
     *
     * @param array $raw_data
     */
    public function __construct(array $raw_data)
    {
        $this->raw_data = $raw_data;
    }

    /**
     * @return int
     */
    public function getConsumersCount(): int
    {
        return (int) ($this->raw_data['consumers'] ?? 0);
    }

    /**
     * @return int
     */
    public function getMessagesCount(): int
    {
        return (int) ($this->raw_data['messages'] ?? 0);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->raw_data['name'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getNodeName()
    {
        return $this->raw_data['node'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getState()
    {
        return $this->raw_data['state'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getVhost()
    {
        return $this->raw_data['vhost'] ?? null;
    }
}
