<?php declare(strict_types=1);

namespace AP\ToObject\Caster;

use AP\ToObject\Error\BaseError;
use AP\ToObject\Error\UnexpectedType;
use AP\ToObject\ToObject;
use Throwable;
use UnexpectedValueException;

readonly class BaseCaster implements CasterInterface
{
    /**
     * @var array<CasterInterface>
     */
    protected array $casters;

    /**
     * @param array<CasterInterface> $casters
     */
    public function __construct(array $casters = [])
    {
        foreach ($casters as $caster) {
            if (!($caster instanceof CasterInterface)) {
                throw new UnexpectedValueException("all casters must implement " . CasterInterface::class);
            }
        }
        $this->casters = $casters;
    }

    /**
     * @param string $expected expected final type see: get_debug_type()
     * @param bool $allowsNull is final value allowed to be null.
     * @param mixed $el link to original data, original data can be changed if cast successful finishes
     * @param ToObject $toObject current mapping settings
     * @return true|array<BaseError>
     * @throws Throwable other fatal errors no related with incoming data
     */
    public function cast(
        string   $expected,
        bool     $allowsNull,
        mixed    &$el,
        ToObject $toObject,
    ): true|array
    {
        if ($allowsNull && is_null($el)) {  // optional params
            return true;
        }

        $actual = get_debug_type($el);

        if ($expected == $actual) { // 100% matching data types
            return true;
        }

        foreach ($this->casters as $caster) {
            $res = $caster->cast(
                $expected,
                $allowsNull,
                $el,
                $toObject,
            );
            if ($res === true) {
                return true;
            }
            if (is_array($res)) {
                return $res;
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
}