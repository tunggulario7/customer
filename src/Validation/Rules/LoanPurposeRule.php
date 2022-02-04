<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Services\LoanPurposeService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanPurposeRule extends AbstractRule
{
    public function validate($input): bool
    {
        $input = (int) $input;
        $validate = v::intType()->notEmpty()->validate($input);

        //Get Loan Purpose Data
        $connection = new Connection();
        $loanPurposeService = new LoanPurposeService($connection);

        $data = $loanPurposeService->getById($input);

        if ($validate && $data) {
            return true;
        }

        return false;
    }

}