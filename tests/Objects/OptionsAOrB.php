<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;

class OptionsAOrB
{
    public function __construct(
        public A|B $option,
    )
    {
    }
}
