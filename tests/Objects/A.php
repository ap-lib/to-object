<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;

use PHPUnit\Framework\TestCase;

class A
{
    public function __construct(
        public string  $a_name,
    )
    {
    }
}
