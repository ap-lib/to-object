<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\Caster\CasterInterface;
use AP\Context\Context;
use AP\ErrorNode\Error;
use AP\ErrorNode\ThrowableErrors;
use AP\ToObject\ToObject;
use RuntimeException;
use Throwable;

/**
 * Caster for transforming nested objects using the same casting strategy
 *
 * - Requires a `Context` instance with a properly set `ToObject`
 * - Can't function as an independent unit due to its dependency on `Context`
 */
readonly class ObjectCaster implements CasterInterface
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
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed $el Reference to the value being cast
     * @param ?Context $context The context, which must contain a `ToObject` instance
     * @return ?array<Error> `true` if successfully cast, `false` to skip, a non-empty array if casting fails
     * @throws Throwable If a fatal error occurs during object construction
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): bool|array
    {
        if (!isset(self::ALLOWED_FOR_OBJ_HASHMAP[get_debug_type($el)])) {
            return false; // actual value is not scalar
        }

        if (!class_exists($expected)) {
            return false; // class doesn't exist
        }

        try {
            if (is_null($context)) {
                // This caster requires a ToObject instance within the context, ensure the context is initialized
                throw new RuntimeException(
                    "A valid Context with a " . ToObject::class . " instance is required for " .
                    ObjectCaster::class
                );
            }
            $el = $context->getObject(ToObject::class)->makeObject(
                $el,
                $expected,
            );
            return true;
        } catch (ThrowableErrors $errors) {
            return $errors->getErrors();
        }
    }
}