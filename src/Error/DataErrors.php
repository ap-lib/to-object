<?php

namespace AP\ToObject\Error;

use Error;
use RuntimeException;

/**
 * Exception thrown when data-related validation or transformation errors occur during casting
 *
 * This exception:
 * - Extends the base `Error` class
 * - Stores a collection of `AP\ErrorNode\Error` instances
 * - Ensures all provided errors are instances of `AP\ErrorNode\Error`
 */
class DataErrors extends Error
{
    protected array $errors;

    /**
     * @param array<\AP\ErrorNode\Error> $errors The list of casting errors encountered
     * @throws RuntimeException If any provided error isn't an instance of `AP\ErrorNode\Error`
     */
    public function __construct(array $errors)
    {
        foreach ($errors as $error) {
            if (!($error instanceof \AP\ErrorNode\Error)) {
                throw new RuntimeException("All cast errors must extend " . \AP\ErrorNode\Error::class);
            }
            $this->errors[] = $error;
        }
        parent::__construct("Casting error");
    }

    /**
     * Retrieves the list of casting errors
     *
     * @return array<\AP\ErrorNode\Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}