<?php declare(strict_types=1);

namespace AP\ToObject\ObjectParser;

use AP\ToObject\Error\DataErrors;
use AP\ToObject\ToObject;
use Throwable;

/**
 * Defines the contract for parsing raw data into object instances
 */
interface ObjectParserInterface
{
    /**
     * Converts raw data into an object of the specified class
     *
     * @template T
     * @param array|string|int|float|bool|null $data The raw data to be converted
     * @param class-string<T> $class The fully qualified class name of the target object
     * @param ToObject $toObject The ToObject instance handling casting and data types validation
     * @param array<string> $path The path to the current data segment within a larger structure, used for error tracking
     * @return T The instantiated and populated object of type `$class`
     * @throws DataErrors If data-related validation or transformation errors occur
     * @throws Throwable If an unexpected fatal error occurs
     */
    public function makeObject(
        array|string|int|float|bool|null $data,
        string                           $class,
        ToObject                         $toObject,
        array                            $path = [],
    ): object;
}