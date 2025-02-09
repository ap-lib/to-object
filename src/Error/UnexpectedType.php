<?php

namespace AP\ToObject\Error;

class UnexpectedType extends BaseError
{
    /**
     * @var string[]
     */
    readonly public array $expected;

    /**
     * @param string|array<string> $expected
     * @param string $actual
     * @param string $path
     */
    public function __construct(
        string|array           $expected,
        readonly public string $actual,
        string                 $path,
    )
    {
        $this->expected = is_string($expected)
            ? explode("|", $expected)
            : $expected;

        $s = count($this->expected) > 1
            ? "s"
            : "";

        $expected = implode("|", $this->expected);

        parent::__construct(
            "Unexpected date type$s, expected `$expected`, actual `$actual`",
            $path,
        );
    }
}