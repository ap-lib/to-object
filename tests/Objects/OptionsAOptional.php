<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;

class OptionsAOptional
{
    public function __construct(
        public ?A $option,
    )
    {
    }
}
