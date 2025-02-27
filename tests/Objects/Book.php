<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;


class Book
{
    public function __construct(
        public string $title,
        public Author $author,
        public Genre  $genre,
        public float  $price,
        public int    $stock
    )
    {
    }
}