<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\RabbitMqApiClient\QueueInfo;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use AvtoDev\RabbitMqApiClient\QueueInfoInterface;

/**
 * @covers \AvtoDev\RabbitMqApiClient\QueueInfo<extended>
 */
class QueueInfoTest extends AbstractTestCase
{
    const NON_SET = '__NOT_SET__';

    /**
     * @var QueueInfo
     */
    protected $queue_info;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queue_info = new QueueInfo($this->getSampleData());
    }

    /**
     * @return void
     */
    public function testImplementations(): void
    {
        $this->assertInstanceOf(QueueInfoInterface::class, $this->queue_info);
        $this->assertInstanceOf(Arrayable::class, $this->queue_info);
        $this->assertInstanceOf(Jsonable::class, $this->queue_info);
    }

    /**
     * @return void
     */
    public function testConstructorAndInputDataGetters(): void
    {
        $queue_info = new QueueInfo($input = ['foo' => 'bar']);

        $this->assertSame($input, $queue_info->toArray());
        $this->assertSame(Json::encode($input), $queue_info->toJson());
    }

    /**
     * @return void
     */
    public function testGetConsumersCount(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            null,
            null,
            null,
            $count = \random_int(1, 99)
        ));

        $this->assertSame($count, $queue_info->getConsumersCount());
    }

    /**
     * @return void
     */
    public function testGetConsumersCountUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertSame(0, $queue_info->getConsumersCount());
    }

    /**
     * @return void
     */
    public function testGetMessagesCount(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            null,
            null,
            null,
            null,
            $count = \random_int(1, 99)
        ));

        $this->assertSame($count, $queue_info->getMessagesCount());
    }

    /**
     * @return void
     */
    public function testGetMessagesCountUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertSame(0, $queue_info->getMessagesCount());
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            $name = Str::random()
        ));

        $this->assertSame($name, $queue_info->getName());
    }

    /**
     * @return void
     */
    public function testGetNameUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertNull($queue_info->getName());
    }

    /**
     * @return void
     */
    public function testGetNodeName(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            null,
            null,
            $node_name = Str::random()
        ));

        $this->assertSame($node_name, $queue_info->getNodeName());
    }

    /**
     * @return void
     */
    public function testGetNodeNameUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertNull($queue_info->getNodeName());
    }

    /**
     * @return void
     */
    public function testGetState(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            null,
            null,
            null,
            null,
            null,
            $state = 'ready'
        ));

        $this->assertSame($state, $queue_info->getState());
    }

    /**
     * @return void
     */
    public function testGetStateUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertNull($queue_info->getState());
    }

    /**
     * @return void
     */
    public function testGetVhost(): void
    {
        $queue_info = new QueueInfo($this->getSampleData(
            null,
            $vhost = '/foo'
        ));

        $this->assertSame($vhost, $queue_info->getVhost());
    }

    /**
     * @return void
     */
    public function testGetVhostUnset(): void
    {
        $queue_info = new QueueInfo($this->getSampleData());

        $this->assertNull($queue_info->getVhost());
    }

    /**
     * @param string $test_queue_name
     * @param string $test_vhost
     * @param string $node_name
     * @param string $consumers_count
     * @param string $messages_count
     * @param string $state
     *
     * @return array<string, mixed>
     */
    protected function getSampleData($test_queue_name = self::NON_SET, //'test-queue',
                                     $test_vhost = self::NON_SET, //'/',
                                     $node_name = self::NON_SET, //'rabbit@ec4beaf96ab6',
                                     $consumers_count = self::NON_SET, //1
                                     $messages_count = self::NON_SET, //1
                                     $state = self::NON_SET //1
    ): array {
        $data = [
            'consumer_details'                => [],
            'arguments'                       => [],
            'auto_delete'                     => false,
            'backing_queue_status'            => [
                'avg_ack_egress_rate'  => 0.0,
                'avg_ack_ingress_rate' => 0.0,
                'avg_egress_rate'      => 0.0,
                'avg_ingress_rate'     => 0.0,
                'delta'                => [
                    'delta',
                    'undefined',
                    0,
                    0,
                    'undefined',
                ],
                'len'                  => 0,
                'mode'                 => 'default',
                'next_seq_id'          => 0,
                'q1'                   => 0,
                'q2'                   => 0,
                'q3'                   => 0,
                'q4'                   => 0,
                'target_ram_count'     => 'infinity',
            ],
            'consumer_utilisation'            => null,
            //'consumers'                     => $consumers_count,
            'deliveries'                      => [],
            'durable'                         => true,
            'effective_policy_definition'     => [],
            'exclusive'                       => false,
            'exclusive_consumer_tag'          => null,
            'garbage_collection'              => [
                'fullsweep_after'    => 65535,
                'max_heap_size'      => 0,
                'min_bin_vheap_size' => 46422,
                'min_heap_size'      => 233,
                'minor_gcs'          => 0,
            ],
            'head_message_timestamp'          => null,
            'idle_since'                      => '2019-02-19 17:01:19',
            'incoming'                        => [],
            'memory'                          => 15080,
            'message_bytes'                   => 0,
            'message_bytes_paged_out'         => 0,
            'message_bytes_persistent'        => 0,
            'message_bytes_ram'               => 0,
            'message_bytes_ready'             => 0,
            'message_bytes_unacknowledged'    => 0,
            'message_stats'                   => [
                'publish'         => 1,
                'publish_details' => [
                    'rate' => 0.0,
                ],
            ],
            //'messages'                      => $messages_count,
            'messages_details'                => [
                'rate' => 0.0,
            ],
            'messages_paged_out'              => 0,
            'messages_persistent'             => 0,
            'messages_ram'                    => 0,
            'messages_ready'                  => 0,
            'messages_ready_details'          => [
                'rate' => 0.0,
            ],
            'messages_ready_ram'              => 0,
            'messages_unacknowledged'         => 0,
            'messages_unacknowledged_details' => [
                'rate' => 0.0,
            ],
            'messages_unacknowledged_ram'     => 0,
            //'name'                          => $test_queue_name,
            //'node'                          => $node_name,
            'operator_policy'                 => null,
            'policy'                          => null,
            'recoverable_slaves'              => null,
            'reductions'                      => 3704,
            'reductions_details'              => [
                'rate' => 0.0,
            ],
            //'state'                         => $state,
            //'vhost'                         => $test_vhost,
        ];

        if ($consumers_count !== self::NON_SET) {
            $data['consumers'] = $consumers_count;
        }

        if ($test_queue_name !== self::NON_SET) {
            $data['name'] = $test_queue_name;
        }

        if ($node_name !== self::NON_SET) {
            $data['node'] = $node_name;
        }

        if ($test_vhost !== self::NON_SET) {
            $data['vhost'] = $test_vhost;
        }

        if ($messages_count !== self::NON_SET) {
            $data['messages'] = $messages_count;
        }

        if ($state !== self::NON_SET) {
            $data['state'] = $state;
        }

        return $data;
    }
}
