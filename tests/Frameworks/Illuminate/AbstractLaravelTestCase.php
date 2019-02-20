<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Tests\Frameworks\Illuminate;

use AvtoDev\RabbitMqApiClient\Tests\Traits\CreatesApplicationTrait;
use AvtoDev\RabbitMqApiClient\Frameworks\Illuminate\LaravelServiceProvider;

abstract class AbstractLaravelTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->app->register(LaravelServiceProvider::class);
    }
}
