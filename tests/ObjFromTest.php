<?php declare(strict_types=1);

namespace AP\ToObject\Tests;

use PHPUnit\Framework\TestCase;

class Book
{
    public function __construct(
        public string  $name,
        public ?int    $id = null,
        public ?Author $author = null,
    )
    {
    }
}

class Author
{
    public function __construct(
        public string $name,
    )
    {

    }
}

final class ObjFromTest extends TestCase
{
    public function testBasic(): void
    {

//        $this->assertEquals(
//            $caster->makeObject(["name" => "hello world"], Book::class),
//            new Book("hello world")
//        );
    }
}
