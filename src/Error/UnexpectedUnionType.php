<?php

namespace AP\ToObject\Error;

class UnexpectedUnionType extends BaseError
{
    /**
     * @param array<string, array<BaseError>> $options
     * @param string $path
     */
    public function __construct(
        readonly public array $options,
        string       $path,
    )
    {
        parent::__construct(
            "Unexpected union types, expected `" . implode("|", array_keys($this->options)) . "`",
            $path,
        );
    }
}