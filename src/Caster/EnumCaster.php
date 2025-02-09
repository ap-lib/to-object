<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\ToObject\Error\BaseError;
use AP\ToObject\Error\UnexpectedType;
use AP\ToObject\ToObject;
use Throwable;

class EnumCaster implements CasterInterface
{
    const array ALLOWED_FOR_ENUM_HASHMAP = [
        "int"    => true,
        "string" => true,
    ];

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
    ): bool|array
    {
        if (enum_exists($expected)) {
            $actual = get_debug_type($el);
            if (
                isset(self::ALLOWED_FOR_ENUM_HASHMAP[$actual])
                && method_exists($expected, "tryFrom")
            ) {
                try {
                    $el = $expected::tryFrom($el);
                    return true;
                } catch (Throwable) {
                }
            }
            return [
                new UnexpectedType(
                    $allowsNull ? [$expected, "null"] : $expected,
                    $actual,
                    ""
                )
            ];
        }
        return false;
    }
}