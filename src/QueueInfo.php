<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient;

class QueueInfo implements QueueInfoInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $raw_data;

    /**
     * Queue Info constructor.
     *
     * @param array<string, mixed> $raw_data
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
        $consumers_count = $this->raw_data['consumers'] ?? 0;

        return \is_numeric($consumers_count) ? \intval($consumers_count) : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessagesCount(): int
    {
        $message_count = $this->raw_data['messages'] ?? 0;

        return \is_numeric($message_count) ? \intval($message_count) : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        $name = $this->raw_data['name'] ?? null;

        return \is_string($name) ? $name : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeName(): ?string
    {
        $node = $this->raw_data['node'] ?? null;

        return \is_string($node) ? $node : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): ?string
    {
        $state = $this->raw_data['state'] ?? null;

        return \is_string($state) ? $state : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVhost(): ?string
    {
        $vhost = $this->raw_data['vhost'] ?? null;

        return \is_string($vhost) ? $vhost : null;
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return (string) \json_encode($this->raw_data, $options);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->raw_data;
    }
}
