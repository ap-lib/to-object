<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Caster;

use AP\Caster\CasterInterface;
use AP\Caster\PrimaryCaster;
use AP\Context\Context;
use AP\ToObject\Caster\ObjectCaster;
use AP\ToObject\ObjectParser\ByConstructor;
use AP\ToObject\Tests\Objects\A;
use AP\ToObject\ToObject;

final class ObjectCasterTest extends BaseCasterTextCase
{
    protected function makeCaster(): CasterInterface
    {
        return new ObjectCaster();
    }

    protected function makeContext(): Context
    {
        return (new Context())
            ->set(
                new ToObject(
                    new ByConstructor(),
                    new PrimaryCaster([
                        new ObjectCaster()
                    ])
                )
            );
    }

    public function testFoundSecond(): void
    {
        $this->assertCasterGood(
            A::class,
            new A("hello world"),
            ["a_name" => "hello world"]
        );

        $this->assertCasterError(A::class, ["hello" => "hello world"]); // no required fields
        $this->assertCasterError(A::class, true); // different type
        $this->assertCasterError(A::class, null); // different type - null
        $this->assertCasterError(A::class, ["a_name" => true]); // no valid a_name, expected string
    }


}
