<?php

namespace App\Exceptions;

class BusinessLogicValidationException extends \Exception
{
    private string $field;
    private string $error;

    public function __construct(string $field, string $error)
    {
        parent::__construct('The given data was invalid.');
        $this->field = $field;
        $this->error = $error;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
