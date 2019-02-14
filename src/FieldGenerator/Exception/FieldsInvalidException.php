<?php

declare(strict_types = 1);

namespace App\FieldGenerator\Exception;

class FieldsInvalidException extends \RuntimeException
{
    protected $errors;

    public function __construct($message = '', array $errors = null)
    {
        parent::__construct($message);
        $this->errors = $errors ? $errors : array();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}