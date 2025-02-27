<?php declare(strict_types=1);

namespace AP\ToObject;

use AP\Caster\PrimaryCaster;
use AP\ToObject\Error\DataErrors;
use AP\ToObject\ObjectParser\ObjectParserInterface;
use Throwable;

/**
 * Converts raw data into structured object instances
 *
 * This class:
 * - Uses an `ObjectParserInterface` to parse and construct objects
 * - Applies a `PrimaryCaster` to transform and validate data types
 */
readonly class ToObject
{
    /**
     * @param ObjectParserInterface $objectParser The object parser responsible for mapping data to object properties
     * @param PrimaryCaster $caster recommended to use PrimarySingleCaster, with additional casters if needed
     */
    public function __construct(
        public ObjectParserInterface $objectParser,
        public PrimaryCaster         $caster = new PrimaryCaster(),
    )
    {
    }

    /**
     * Converts raw data into an object of the specified class
     *
     * @template T
     * @param array|string|int|float|bool|null $data The raw data to be converted into an object
     * @param class-string<T> $class The fully qualified class name of the target object
     * @param array<string> $path The path to the current data segment within a larger structure, used for error tracking
     * @return T The instantiated and populated object of type `$class`
     * @throws DataErrors If data-related validation or transformation errors occur
     * @throws Throwable If an unexpected fatal error occurs
     */
    public function makeObject(
        array|string|int|float|bool|null $data,
        string                           $class,
        array                            $path = [],
    ): object
    {
        return $this->objectParser->makeObject($data, $class, $this, $path);
    }
}