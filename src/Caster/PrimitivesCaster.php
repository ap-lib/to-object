<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\ToObject\Error\BaseError;
use AP\ToObject\ToObject;

class PrimitivesCaster implements CasterInterface
{
    /**
     * @param string $expected
     * @param bool $allowsNull
     * @param mixed $el
     * @param ToObject $toObject
     * @return ?array<BaseError> null - skip, [] - el was casted, [errors]  - errors
     */
    public function cast(
        string   $expected,
        bool     $allowsNull,
        mixed    &$el,
        ToObject $toObject,
    ):bool|array
    {
        switch ($expected) {
            case "int":
                if (is_numeric($el)) {
                    $el = (int)$el;
                    return true;
                }
                break;
            case "float":
                if (is_numeric($el)) {
                    $el = (float)$el;
                    return true;
                }
                break;
            case "string":
                if (is_int($el)) {
                    $el = (string)$el;
                    return true;
                }
                break;
            case "bool":
                if ($el === 0 || $el === 1) {
                    $el = (bool)$el;
                    return true;
                }
        }

        return false;
    }
}