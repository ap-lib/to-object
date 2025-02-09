<?php declare(strict_types=1);

namespace AP\ToObject\ObjectParser;

use AP\ToObject\Error\DataErrors;
use AP\ToObject\ToObject;
use Throwable;

interface ObjectParserInterface
{
    /**
     * @template T
     * @param array|string|int|float|bool|null $data
     * @param class-string<T> $class
     * @param string $path
     * @param ToObject $toObject
     * @return T
     * @throws DataErrors data errors
     * @throws Throwable all other no related with data errors
     */
    public function makeObject(
        array|string|int|float|bool|null $data,
        string                           $class,
        ToObject                         $toObject,
        string                           $path = "",
    ): object;
}