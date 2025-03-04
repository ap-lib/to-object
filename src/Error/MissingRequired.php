<?php

namespace AP\ToObject\Error;

use AP\ErrorNode\Error;

/**
 * Exception thrown when a required field is missing during casting operations
 */
class MissingRequired extends Error
{
    /**
     * @param array<string> $path The path to the missing field within the data structure
     */
    public function __construct(array $path = [])
    {
        parent::__construct(
            "Missing required field",
            $path,
        );
    }
}