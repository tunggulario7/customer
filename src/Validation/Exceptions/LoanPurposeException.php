<?php

declare(strict_types=1);

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class LoanPurposeException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} not be processed.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} does not exist.',
        ],
    ];
}