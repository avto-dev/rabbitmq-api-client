<?php

declare(strict_types = 1);

namespace AvtoDev\RabbitMqApiClient\Exceptions;

use Throwable;

class HttpException extends \RuntimeException
{
    /**
     * @param string         $uri
     * @param int            $code
     * @param Throwable|null $previous
     *
     * @return HttpException
     */
    public static function failed(string $uri, int $code = 0, Throwable $previous = null): self
    {
        return new static("Request to the uri [{$uri}] failed!", $code, $previous);
    }
}
