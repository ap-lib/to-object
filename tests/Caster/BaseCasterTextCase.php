<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Caster;

use AP\Caster\CasterInterface;
use AP\Context\Context;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class BaseCasterTextCase extends TestCase
{
    abstract protected function makeCaster(): CasterInterface;

    abstract protected function makeContext(): Context;

    /**
     * @throws Throwable
     */
    public function assertCasterGood(
        string $expectedType,
        mixed  $expected,
        mixed  $el
    ): void
    {
        $res = $this->makeCaster()->cast($expectedType, $el, $this->makeContext());
        $this->assertTrue($res);
        $this->assertEquals($expected, $el);
    }

    /**
     * @throws Throwable
     */
    public function assertCasterError(
        string $expectedType,
        mixed  $el
    ): void
    {
        $expected = $el;
        $res      = $this->makeCaster()->cast($expectedType, $el, $this->makeContext());
        $this->assertTrue(is_array($res) && count($res));

        // IMPORTANT. The caster mustn't modify the element if casting was unsuccessful
        $this->assertEquals($expected, $el);
    }
}
