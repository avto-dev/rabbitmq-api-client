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

    /**
     * Get a instance property (public/private/protected) value.
     *
     * @param object $object
     * @param string $property_name
     *
     * @return mixed
     */
    protected function getProperty($object, string $property_name)
    {
        $reflection = new \ReflectionClass($object);

        $property = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
