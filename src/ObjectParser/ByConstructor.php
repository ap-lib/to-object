<?php declare(strict_types=1);

namespace AP\ToObject\ObjectParser;

use AP\Caster\Error\UnexpectedType;
use AP\Context\Context;
use AP\ToObject\Error\DataErrors;
use AP\ToObject\Error\MissingRequired;
use AP\ToObject\Error\UnexpectedUnionType;
use AP\ToObject\ToObject;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;
use Throwable;

/**
 * Parses data and creates an object instance using its constructor
 *
 * This class:
 * - Extracts constructor parameters from given class
 * - Maps input data to constructor arguments
 */
class ByConstructor implements ObjectParserInterface
{
    public function __construct(
        protected bool $allowed_map_empty_to_null = true
    )
    {
    }

    /**
     * Converts a path array into a string representation for use in critical exceptions
     * where array-based paths aren't allowed
     *
     * @param array $path
     * @return string
     */
    private static function spath(array $path): string
    {
        return implode(".", $path);
    }

    /**
     * Creates an object of the specified class using its constructor
     *
     * This method:
     * - Checks if the target class has a constructor
     * - Resolves constructor parameters, applying type casting as needed
     * - Throws `DataErrors` if required parameters are missing or invalid
     *
     * @template T
     * @param array|string|int|float|bool|null $data The input data to be converted into an object
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
    ): object
    {
        if (!is_array($data)) {
            throw new DataErrors([
                new UnexpectedType(
                    "array",
                    get_debug_type($data),
                    [],
                )
            ]);
        }

        $reflection  = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            throw new RuntimeException("Class `$class` has no constructor.");
        }

        $params  = $constructor->getParameters();
        $args    = [];
        $errors  = [];
        $context = (new Context())->set($toObject);


        foreach ($params as $param) {
            $name = $param->getName();
            if (!array_key_exists($name, $data)) {
                if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } elseif (
                    $this->allowed_map_empty_to_null
                    && (
                        $param->getType() instanceof ReflectionNamedType
                        || $param->getType() instanceof ReflectionUnionType
                    )
                    && $param->getType()->allowsNull()
                ) {
                    $args[] = null;
                } else {
                    $errors[] = new MissingRequired(
                        array_merge($path, [$name])
                    );
                }
            } else {
                $typeRef = $param->getType();
                if ($typeRef instanceof ReflectionNamedType) {
                    // Single type casting
                    if ($typeRef->allowsNull() && is_null($data[$name])) {
                        $args[] = $data[$name];
                        continue;
                    }

                    $castRes = $toObject->caster->cast(
                        $typeRef->getName(),
                        $data[$name],
                        $context,
                    );
                    if ($castRes === true) {
                        $args[] = $data[$name];
                    } elseif (is_array($castRes) && count($castRes)) {
                        foreach ($castRes as $castError) {
                            $castError->path = array_merge($path, [$name], $castError->path);
                            $errors[]        = $castError;
                        }
                    } else {
                        $errors[] = new UnexpectedType(
                            $typeRef->getName(),
                            get_debug_type($data[$name]),
                            []
                        );
                    }
                } elseif ($typeRef instanceof ReflectionUnionType) {
                    // Union type casting
                    if ($typeRef->allowsNull() && is_null($data[$name])) {
                        $args[] = $data[$name];
                        continue;
                    }

                    $casted  = false;
                    $options = [];

                    foreach ($typeRef->getTypes() as $typeRefEl) {
                        if ($typeRefEl instanceof ReflectionNamedType) {
                            $castRes = $toObject->caster->cast(
                                $typeRefEl->getName(),
                                $data[$name],
                                $context,
                            );
                            if ($castRes === true) {
                                $casted = true;
                                $args[] = $data[$name];
                                break;
                            } elseif (is_array($castRes) && count($castRes)) {
                                foreach ($castRes as $castError) {
                                    $castError->path = array_merge($path, [$name], $castError->path);
                                }
                                $options[$typeRefEl->getName()] = $castRes;
                            } else {
                                $options[$typeRefEl->getName()] = new UnexpectedType(
                                    $typeRefEl->getName(),
                                    get_debug_type($data[$name]),
                                );
                            }
                        } else {
                            throw new RuntimeException(
                                self::spath(array_merge($path, [$name])) . ": unsupportable parameter type"
                            );
                        }
                    }
                    if (!$casted) {
                        $errors[] = new UnexpectedUnionType(
                            $options,
                            array_merge($path, [$name]),
                        );
                    }
                } elseif (is_null($typeRef)) {
                    // Mixed type, no type declaration
                    $args[] = $data[$name];
                } else {
                    throw new RuntimeException(
                        self::spath(array_merge($path, [$name])) . ": unsupportable parameter type"
                    );
                }
            }
        }
        if (!empty($errors)) {
            throw new DataErrors($errors);
        }

        return $reflection->newInstanceArgs($args);
    }
}