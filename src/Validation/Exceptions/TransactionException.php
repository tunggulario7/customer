<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class TransactionException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} not be processed.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} Transaction not valid.',
        ],
    ];
}