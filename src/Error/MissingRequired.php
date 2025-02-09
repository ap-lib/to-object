<?php

namespace AP\ToObject\Error;

class MissingRequired extends BaseError
{
    public function __construct(string $path)
    {
        parent::__construct(
            "Missing required field",
            $path,
        );
    }
}