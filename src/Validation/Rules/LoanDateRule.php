<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanDateRule extends AbstractRule
{
    public function validate($input): bool
    {
        $validate = v::date()->notEmpty()->validate($input);

        if ($validate && strtotime($input) >= strtotime(date("Y-m-d"))) {
            return true;
        }

        return false;
    }
}
