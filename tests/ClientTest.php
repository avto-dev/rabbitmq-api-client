<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests;

use AvtoDev\RabbitMqApiClient\Client;
use AvtoDev\RabbitMqApiClient\ConnectionSettings;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = new Client(new ConnectionSettings('http://127.0.0.1:15672'));
    }

    /**
     * @return void
     */
    public function testPing()
    {
        $this->markTestIncomplete();
        //$this->client->healthcheck();
    }

    /**
     * @return void
     */
    public function testMessagesCount()
    {
        $this->markTestIncomplete();
        //$this->client->queueInfo('test1');
    }
}
