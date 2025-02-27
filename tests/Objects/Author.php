<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;

class Author
{
    public function __construct(
        public string $name,
        public string $bio,
    )
    {
    }
}
