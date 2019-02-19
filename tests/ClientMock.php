<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class ClientMock extends Client
{
    /**
     * @var MockHandler
     */
    public $mock_handler;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConnectionSettings $settings, array $guzzle_config = [])
    {
        $this->mock_handler = new MockHandler;

        parent::__construct($settings, \array_replace($guzzle_config, [
            'handler' => HandlerStack::create($this->mock_handler),
        ]));
    }

    /**
     * @param string $queue_name
     * @param string $vhost
     *
     * @return string
     */
    public static function queueInfoStub(string $queue_name, string $vhost = '/'): string
    {
        return '{"consumer_details":[],"arguments":{},"auto_delete":false,"backing_queue_status":{' .
               '"avg_ack_egress_rate":0.0,"avg_ack_ingress_rate":0.0,"avg_egress_rate":0.0,"avg_ingress_rate":0.0,' .
               '"delta":["delta","undefined",0,0,"undefined"],"len":0,"mode":"default","next_seq_id":0,"q1":0,' .
               '"q2":0,"q3":0,"q4":0,"target_ram_count":"infinity"},"consumer_utilisation":null,"consumers":0,' .
               '"deliveries":[],"durable":true,"effective_policy_definition":[],"exclusive":false,' .
               '"exclusive_consumer_tag":null,"garbage_collection":{"fullsweep_after":65535,"max_heap_size":0,' .
               '"min_bin_vheap_size":46422,"min_heap_size":233,"minor_gcs":0},"head_message_timestamp":null,' .
               '"idle_since":"2019-02-19 17:01:19","incoming":[],"memory":15080,"message_bytes":0,' .
               '"message_bytes_paged_out":0,"message_bytes_persistent":0,"message_bytes_ram":0,' .
               '"message_bytes_ready":0,"message_bytes_unacknowledged":0,"message_stats":{"publish":1,' .
               '"publish_details":{"rate":0.0}},"messages":0,"messages_details":{"rate":0.0},"messages_paged_out":0,' .
               '"messages_persistent":0,"messages_ram":0,"messages_ready":0,"messages_ready_details":{"rate":0.0},' .
               '"messages_ready_ram":0,"messages_unacknowledged":0,"messages_unacknowledged_details":{"rate":0.0},' .
               '"messages_unacknowledged_ram":0,"name":"' . $queue_name . '","node":"rabbit@ec4beaf96ab6",' .
               '"operator_policy":null,"policy":null,"recoverable_slaves":null,"reductions":3704,' .
               '"reductions_details":{"rate":0.0},"state":"running","vhost":"' . $vhost . '"}';
    }
}
