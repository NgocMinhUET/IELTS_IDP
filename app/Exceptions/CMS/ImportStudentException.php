<?php

namespace App\Exceptions\CMS;

use Exception;

class ImportStudentException extends Exception
{
    protected $message = 'Import student failed';

    protected $code = 400;

    protected array $errors = [];
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct($this->message, $this->code);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}