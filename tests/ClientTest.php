<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use GuzzleHttp\Psr7\Response;
use Tarampampam\Wrappers\Json;
use AvtoDev\RabbitMqApiClient\QueueInfo;
use GuzzleHttp\Exception\RequestException;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;
use Tarampampam\Wrappers\Exceptions\JsonEncodeDecodeException;

class ClientTest extends AbstractTestCase
{
    /**
     * @var ClientMock
     */
    protected $client;

    /**
     * @var string
     */
    protected $queue_name = 'test-queue';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = new ClientMock(new ConnectionSettings('http://127.0.0.1:15672'));
    }

    /**
     * @return void
     */
    public function testPingSuccess()
    {
        $this->client->mock_handler->append(
            new Response(200, ['content-type' => 'application/json'], Json::encode([
                'status' => 'ok',
            ]))
        );

        $this->assertTrue($this->client->healthcheck());
    }

    /**
     * @return void
     */
    public function testPingFailed()
    {
        $this->client->mock_handler->append(
            new Response(200, ['content-type' => 'application/json'], Json::encode([
                'status' => 'failed',
                'reason' => 'Something goes wrong',
            ]))
        );

        $this->assertFalse($this->client->healthcheck());
    }

    /**
     * @return void
     */
    public function testPingWithWrongJsonResponse()
    {
        $this->expectException(JsonEncodeDecodeException::class);

        $this->client->mock_handler->append(
            new Response(200, ['content-type' => 'application/json'], '{"status":"...')
        );

        $this->client->healthcheck();
    }

    /**
     * @return void
     */
    public function testPingWithServerError()
    {
        $this->expectException(RequestException::class);

        $this->client->mock_handler->append(
            new Response(500, [], 'Service unavailable')
        );

        $this->client->healthcheck();
    }

    /**
     * @return void
     */
    public function testQueueInfoSuccess()
    {
        $this->client->mock_handler->append(
            new Response(200, ['content-type' => 'application/json'], ClientMock::queueInfoStub($this->queue_name))
        );

        $this->assertInstanceOf(QueueInfo::class, $info = $this->client->queueInfo($this->queue_name));
        $this->assertSame($this->queue_name, $info->getName());
    }

    /**
     * @return void
     */
    public function testQueueInfoWithWrongJsonResponse()
    {
        $this->expectException(JsonEncodeDecodeException::class);

        $this->client->mock_handler->append(
            new Response(200, ['content-type' => 'application/json'], '{"data":"...')
        );

        $this->client->queueInfo($this->queue_name);
    }

    /**
     * @return void
     */
    public function testQueueInfoWithServerError()
    {
        $this->expectException(RequestException::class);

        $this->client->mock_handler->append(
            new Response(500, [], 'Service unavailable')
        );

        $this->client->queueInfo($this->queue_name);
    }
}
