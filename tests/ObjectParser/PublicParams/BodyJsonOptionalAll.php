<?php declare(strict_types=1);

namespace AP\ToObject\Tests\ObjectParser\PublicParams;

use AP\ToObject\ObjectParser\AllowEmpty;

#[AllowEmpty]
class BodyJsonOptionalAll
{
    public string $label;
    public int    $order;
    public bool   $active;
}
