<?php declare(strict_types=1);

namespace AP\ToObject\ObjectParser;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
readonly class AllowEmpty
{
    public function __construct(
        public bool $allowed_empty = true
    )
    {
    }
}