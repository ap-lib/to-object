<?php

namespace AP\ToObject\Error;

class BaseError
{
    /**
     * @param string $message
     * @param string $path
     */
    public function __construct(
        readonly public string $message,
        public string          $path,
    )
    {
    }
}