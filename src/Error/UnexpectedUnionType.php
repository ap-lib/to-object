<?php

namespace AP\ToObject\Error;

use AP\Caster\Error\CastError;

/**
 * Exception thrown when a value doesn't match any of the expected union types during casting operations
 *
 * This exception:
 * - Indicates that a value failed to match all provided union types
 * - Contains detailed errors for each attempted type
 */
class UnexpectedUnionType extends CastError
{
    /**
     * @param array<string, array<CastError>> $options An associative array where each key is an expected type,
     *                  and the corresponding value is a list of CastError instances encountered for that type
     * @param array<string> $path The path to the value within the data structure
     */
    public function __construct(
        readonly public array $options,
        array                 $path,
    )
    {
        parent::__construct(
            "Unexpected union types, expected `" .
            implode("|", array_keys($this->options)) . "`",
            $path,
        );
    }
}