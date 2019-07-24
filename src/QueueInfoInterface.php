<?php

namespace AvtoDev\RabbitMqApiClient;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

interface QueueInfoInterface extends Jsonable, Arrayable
{
    /**
     * Get consumers count.
     *
     * @return int
     */
    public function getConsumersCount(): int;

    /**
     * Get messages in queue count.
     *
     * @return int
     */
    public function getMessagesCount(): int;

    /**
     * Get queue name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get node name.
     *
     * @return string|null
     */
    public function getNodeName(): ?string;

    /**
     * Get queue state value.
     *
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * Get queue vhost value.
     *
     * @return string|null
     */
    public function getVhost(): ?string;
}
