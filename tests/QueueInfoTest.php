<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use Tarampampam\Wrappers\Json;
use AvtoDev\RabbitMqApiClient\QueueInfo;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use AvtoDev\RabbitMqApiClient\QueueInfoInterface;

/**
 * @coversDefaultClass \AvtoDev\RabbitMqApiClient\QueueInfo
 */
class QueueInfoTest extends AbstractTestCase
{
    /**
     * @var QueueInfo
     */
    protected $queue_info;

    /**
     * @var string
     */
    protected $test_queue_name = 'test-queue';

    /**
     * @var string
     */
    protected $test_vhost = '/';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->queue_info = new QueueInfo(Json::decode(
            ClientMock::queueInfoStub($this->test_queue_name, $this->test_vhost)
        ));
    }

    /**
     * @return void
     */
    public function testImplementations()
    {
        $this->assertInstanceOf(QueueInfoInterface::class, $this->queue_info);
        $this->assertInstanceOf(Arrayable::class, $this->queue_info);
        $this->assertInstanceOf(Jsonable::class, $this->queue_info);
    }

    /**
     * @return void
     */
    public function testConstructorAndInputDataGetters()
    {
        $queue_info = new QueueInfo($input = ['foo' => 'bar']);

        $this->assertSame($input, $queue_info->toArray());
        $this->assertSame(Json::encode($input), $queue_info->toJson());
    }
}
