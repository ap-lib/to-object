<?php

namespace AP\ToObject\Error;

use Error;
use RuntimeException;

class DataErrors extends Error
{
    protected array $errors;

    /**
     * @param array<BaseError> $errors
     */
    public function __construct(array $errors)
    {
        foreach ($errors as $error) {
            if (!($error instanceof BaseError)) {
                throw new RuntimeException("all cast errors must extends " . BaseError::class);
            }
            $this->errors[] = $error;
        }
        parent::__construct("casting error");
    }

    /**
     * @return array<BaseError>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}