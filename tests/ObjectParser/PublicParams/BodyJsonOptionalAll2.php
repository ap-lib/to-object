<?php declare(strict_types=1);

namespace AP\ToObject\Tests\ObjectParser\PublicParams;


use AP\ToObject\ObjectParser\AllowEmpty;

class BodyJsonOptionalAll2
{
    #[AllowEmpty]
    public string $label;

    #[AllowEmpty]
    public int    $order;

    #[AllowEmpty]
    public bool   $active;
}
