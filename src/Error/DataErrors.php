<?php

namespace AP\ToObject\Error;

use AP\Caster\Error\CastError;
use Error;
use RuntimeException;

/**
 * Exception thrown when data-related validation or transformation errors occur during casting
 *
 * This exception:
 * - Extends the base `Error` class
 * - Stores a collection of `CastError` instances
 * - Ensures all provided errors are instances of `CastError`
 */
class DataErrors extends Error
{
    protected array $errors;

    /**
     * @param array<CastError> $errors The list of casting errors encountered
     * @throws RuntimeException If any provided error isn't an instance of `CastError`
     */
    public function __construct(array $errors)
    {
        foreach ($errors as $error) {
            if (!($error instanceof CastError)) {
                throw new RuntimeException("All cast errors must extend " . CastError::class);
            }
            $this->errors[] = $error;
        }
        parent::__construct("Casting error");
    }

    /**
     * Retrieves the list of casting errors
     *
     * @return array<CastError>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}