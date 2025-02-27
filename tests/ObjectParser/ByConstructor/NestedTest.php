<?php declare(strict_types=1);

namespace AP\ToObject\Tests\ObjectParser\ByConstructor;

use AP\Caster\EnumCaster;
use AP\Caster\PrimaryCaster;
use AP\ToObject\Caster\ObjectCaster;
use AP\ToObject\ObjectParser\ByConstructor;
use AP\ToObject\Tests\Objects\Author;
use AP\ToObject\Tests\Objects\Book;
use AP\ToObject\Tests\Objects\Genre;
use AP\ToObject\ToObject;
use PHPUnit\Framework\TestCase;

final class NestedTest extends TestCase
{
    public function testNotFound(): void
    {
        $toObject = new ToObject(
            new ByConstructor(),
            new PrimaryCaster([
                new EnumCaster(),
                new ObjectCaster(),
            ])
        );

        $this->assertEquals(
            new Book(
                "Foundation",
                new Author(
                    "Isaac Asimov",
                    "Science fiction writer, best known for the Foundation series."
                ),
                Genre::FANTASY,
                15.99,
                10
            ),
            $toObject->makeObject(
                [
                    'title'  => "Foundation",
                    'author' => [
                        'name' => "Isaac Asimov",
                        'bio'  => "Science fiction writer, best known for the Foundation series."
                    ],
                    'genre'  => "Fantasy",
                    'price'  => 15.99,
                    'stock'  => 10
                ],
                Book::class
            )
        );
    }
}
