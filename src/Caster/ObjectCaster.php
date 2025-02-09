<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\ToObject\Error\BaseError;
use AP\ToObject\Error\DataErrors;
use AP\ToObject\ToObject;
use Throwable;

class ObjectCaster implements CasterInterface
{
    const array ALLOWED_FOR_OBJ_HASHMAP = [
        "array"  => true,
        "int"    => true,
        "float"  => true,
        "string" => true,
        "bool"   => true,
        "null"   => true,
    ];

    /**
     * @param string $expected
     * @param bool $allowsNull
     * @param mixed $el
     * @param ToObject $toObject
     * @return ?array<BaseError> null - skip, [] - el was casted, [errors]  - errors
     * @throws Throwable fatal errors related with object setup
     */
    public function cast(
        string   $expected,
        bool     $allowsNull,
        mixed    &$el,
        ToObject $toObject,
    ): bool|array
    {
        if (isset(self::ALLOWED_FOR_OBJ_HASHMAP[get_debug_type($el)]) && class_exists($expected)) {
            try {
                $el = $toObject->makeObject(
                    $el,
                    $expected,
                );
                return true;
            } catch (DataErrors $errors) {
                return $errors->getErrors();
            }
        }
        return false;
    }
}