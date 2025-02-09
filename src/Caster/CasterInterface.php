<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\ToObject\Error\BaseError;
use AP\ToObject\ToObject;
use Throwable;

/**
 * additional casters to implement no primitives cast logic
 */
interface CasterInterface
{
    /**
     * @param string $expected
     * @param bool $allowsNull
     * @param mixed $el
     * @param ToObject $toObject
     * @return bool|array<BaseError> false - skip, true - was casted, [errors]  - errors
     * @throws Throwable other fatal errors no related with incoming data
     */
    public function cast(
        string   $expected,
        bool     $allowsNull,
        mixed    &$el,
        ToObject $toObject,
    ): bool|array;
}