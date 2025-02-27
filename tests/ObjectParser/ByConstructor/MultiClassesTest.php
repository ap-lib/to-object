<?php declare(strict_types=1);

namespace AP\ToObject\Tests\ObjectParser\ByConstructor;

use AP\Caster\PrimaryCaster;
use AP\ToObject\Caster\ObjectCaster;
use AP\ToObject\Error\DataErrors;
use AP\ToObject\ObjectParser\ByConstructor;
use AP\ToObject\Tests\Objects\A;
use AP\ToObject\Tests\Objects\B;
use AP\ToObject\Tests\Objects\OptionsAOrB;
use AP\ToObject\ToObject;
use PHPUnit\Framework\TestCase;

final class MultiClassesTest extends TestCase
{
    public function testNotFound(): void
    {
        $this->expectException(DataErrors::class);

        $toObject = new ToObject(
            new ByConstructor(),
            new PrimaryCaster([
                new ObjectCaster(),
            ])
        );

        $toObject->makeObject(
            [
                "c_name"
            ],
            OptionsAOrB::class
        );
    }

    public function testFoundFirst(): void
    {
        $toObject = new ToObject(
            new ByConstructor(),
            new PrimaryCaster([
                new ObjectCaster(),
            ])
        );

        $res = $toObject->makeObject(
            [
                "option" => [
                    "a_name" => "hello"
                ],
            ],
            OptionsAOrB::class
        );

        $this->assertTrue($res->option instanceof A);
    }

    public function testFoundSecond(): void
    {
        $toObject = new ToObject(
            new ByConstructor(),
            new PrimaryCaster([
                new ObjectCaster(),
            ])
        );

        $res = $toObject->makeObject(
            [
                "option" => [
                    "b_name" => "hello"
                ],
            ],
            OptionsAOrB::class
        );

        $this->assertTrue($res->option instanceof B);
    }
}
