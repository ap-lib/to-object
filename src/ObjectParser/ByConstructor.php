<?php declare(strict_types=1);

namespace AP\ToObject\ObjectParser;

use AP\ToObject\Error\DataErrors;
use AP\ToObject\Error\MissingRequired;
use AP\ToObject\Error\UnexpectedType;
use AP\ToObject\Error\UnexpectedUnionType;
use AP\ToObject\ToObject;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;
use Throwable;

class ByConstructor implements ObjectParserInterface
{
    public function __construct(
        protected bool $allowed_map_empty_to_null = true
    )
    {
    }

//    public static function pathString(array $path, string $name = ""): string
//    {
//        return implode(".", BaseError::makePath($path, $name));
//    }

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
    ): object
    {
        if (!is_array($data)) {
            throw new DataErrors([
                new UnexpectedType(
                    "array",
                    get_debug_type($data),
                    "",
                )
            ]);
        }

        $reflection  = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            throw new RuntimeException("Class `$class` has no constructor.");
        }

        $params = $constructor->getParameters();

        $args = [];

        $errors = [];
        foreach ($params as $param) {
            $name = $param->getName();
            if (!array_key_exists($name, $data)) {
                if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } elseif ($this->allowed_map_empty_to_null && $param->getType()->allowsNull()) {
                    $args[] = null;
                } else {
                    $errors[] = new MissingRequired("$path/$name");
                }
            } else {
                $typeRef = $param->getType();
                if ($typeRef instanceof ReflectionNamedType) {
                    // one type
                    $castRes = $toObject->caster->cast(
                        $typeRef->getName(),
                        $typeRef->allowsNull(),
                        $data[$name],
                        $toObject,
                    );
                    if ($castRes === true) {
                        $args[] = $data[$name];
                    } elseif (is_array($castRes) && count($castRes)) {
                        foreach ($castRes as $castError) {
                            $castError->path = "$path/$name$castError->path";
                            $errors[]        = $castError;
                        }
                    } else {
                        $errors[] = new UnexpectedType(
                            $typeRef->allowsNull() ? [$typeRef->getName(), "null"] : $typeRef->getName(),
                            get_debug_type($data[$name]),
                            ""
                        );
                    }
                } elseif ($typeRef instanceof ReflectionUnionType) {
                    // union types
                    $casted  = false;
                    $options = [];
                    foreach ($typeRef->getTypes() as $typeRefEl) {
                        if ($typeRefEl instanceof ReflectionNamedType) {
                            $castRes = $toObject->caster->cast(
                                $typeRefEl->getName(),
                                $typeRefEl->allowsNull(),
                                $data[$name],
                                $toObject,
                            );
                            if ($castRes === true) {
                                $casted = true;
                                $args[] = $data[$name];
                                break;
                            } elseif (is_array($castRes) && count($castRes)) {
                                foreach ($castRes as $castError) {
                                    $castError->path = "$path/$name$castError->path";
                                }
                                $options[$typeRefEl->getName()] = $castRes;
                            } else {
                                $options[$typeRefEl->getName()] = new UnexpectedType(
                                    $typeRef->allowsNull() ? [$typeRefEl->getName(), "null"] : $typeRefEl->getName(),
                                    get_debug_type($data[$name]),
                                    ""
                                );
                            }
                        } else {
                            throw new RuntimeException(
                                "$path/$name: unsupportable param type"
                            );
                        }
                    }
                    if (!$casted) {
                        $errors[] = new UnexpectedUnionType(
                            $options,
                            "$path/$name",
                        );
                    }
                } elseif (is_null($typeRef)) {
                    // allowed mixed param type
                    $args[] = $data[$name];
                } else {
                    throw new RuntimeException(
                        "$path/$name: unsupportable param type"
                    );
                }
            }
        }
        if (count($errors)) {
            throw new DataErrors($errors);
        }

        return $reflection->newInstanceArgs($args);
    }
}