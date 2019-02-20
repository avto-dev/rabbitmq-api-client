<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

use Tarampampam\Wrappers\Json;

class QueueInfo implements QueueInfoInterface
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
     * {@inheritdoc}
     */
    public function getConsumersCount(): int
    {
        return (int) ($this->raw_data['consumers'] ?? 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessagesCount(): int
    {
        return (int) ($this->raw_data['messages'] ?? 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->raw_data['name'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeName()
    {
        return $this->raw_data['node'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->raw_data['state'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVhost()
    {
        return $this->raw_data['vhost'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return Json::encode($this->raw_data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->raw_data;
    }
}
