<?php declare(strict_types=1);

namespace AP\ToObject;

use AP\ToObject\Caster\BaseCaster;
use AP\ToObject\Error\DataErrors;
use AP\ToObject\ObjectParser\ObjectParserInterface;
use Throwable;

readonly class ToObject
{
    /**
     * @param ObjectParserInterface $objectParser
     * @param BaseCaster $caster recommended to use BaseCaster, with additional casters if needed
     */
    public function __construct(
        public ObjectParserInterface $objectParser,
        public BaseCaster            $caster = new BaseCaster(),
    )
    {
    }

    /**
     * @template T
     * @param array|string|int|float|bool|null $data
     * @param class-string<T> $class
     * @param string $path
     * @return T
     * @throws DataErrors error related with incoming data
     * @throws Throwable other fatal errors
     */
    public function makeObject(
        array|string|int|float|bool|null $data,
        string                           $class,
        string                           $path = "",
    ): object
    {
        return $this->objectParser->makeObject(
            $data,
            $class,
            $this,
            $path
        );
    }
}