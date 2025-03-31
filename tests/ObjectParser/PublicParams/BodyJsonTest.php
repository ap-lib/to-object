<?php declare(strict_types=1);

namespace AP\ToObject\Tests\ObjectParser\PublicParams;

use AP\Caster\EnumCaster;
use AP\Caster\PrimaryCaster;
use AP\ErrorNode\ThrowableErrors;
use AP\ToObject\Caster\ObjectCaster;
use AP\ToObject\Error\MissingRequired;
use AP\ToObject\ObjectParser\PublicParams;
use AP\ToObject\ToObject;
use PHPUnit\Framework\TestCase;

final class BodyJsonTest extends TestCase
{
    protected function makeToObject(): ToObject
    {
        return new ToObject(
            new PublicParams(),
            new PrimaryCaster([
                new EnumCaster(),
                new ObjectCaster(),
            ])
        );
    }

    public function testGood(): void
    {
        $data = [
            'label'  => "first",
            'order'  => 1,
            'active' => true,
        ];

        $obj = $this->makeToObject()->makeObject(
            $data,
            BodyJson::class
        );

        $this->assertEquals('first', $obj->label);
        $this->assertEquals(1, $obj->order);
        $this->assertEquals(true, $obj->active);
    }

    public function testError(): void
    {
        $this->expectException(ThrowableErrors::class);
        $data = [
            'label' => "first",
            'order' => 1,
        ];

        try {
            $this->makeToObject()->makeObject(
                $data,
                BodyJson::class
            );
        } catch (ThrowableErrors $e) {
            $this->assertInstanceOf(MissingRequired::class, $e->getErrors()[0]);
            $this->assertEquals(["active"], $e->getErrors()[0]->path);
            throw $e;
        }
    }

    public function testOptionalAllGood(): void
    {
        $data = [
            'label'  => "first",
            'order'  => 1,
            'active' => true,
        ];

        $obj = $this->makeToObject()->makeObject(
            $data,
            BodyJsonOptionalAll::class
        );

        $this->assertEquals('first', $obj->label);
        $this->assertEquals(1, $obj->order);
        $this->assertEquals(true, $obj->active);
    }

    public function testOptionalAllPartial(): void
    {
        $data = [
            'label' => "first",

        ];

        $obj = $this->makeToObject()->makeObject(
            $data,
            BodyJsonOptionalAll::class
        );

        $this->assertEquals('first', $obj->label);
    }

    public function testOptionalAll2Good(): void
    {
        $data = [
            'label'  => "first",
            'order'  => 1,
            'active' => true,
        ];

        $obj = $this->makeToObject()->makeObject(
            $data,
            BodyJsonOptionalAll2::class
        );

        $this->assertEquals('first', $obj->label);
        $this->assertEquals(1, $obj->order);
        $this->assertEquals(true, $obj->active);
    }

    public function testOptionalAll2Partial(): void
    {
        $data = [
            'label' => "first",

        ];

        $obj = $this->makeToObject()->makeObject(
            $data,
            BodyJsonOptionalAll2::class
        );

        $this->assertEquals('first', $obj->label);
    }
}
