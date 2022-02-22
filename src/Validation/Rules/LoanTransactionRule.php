<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Modules\LoanTransaction\Provider\LoanTransactionProvider;
use App\Modules\LoanTransaction\Service\LoanTransactionService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanTransactionRule extends AbstractRule
{
    public function validate($input): bool
    {
        $input = (int) $input;
        $transaction = v::intType()->notEmpty()->validate($input);

        //Get Loan Transaction Service
        $connection = new Connection();
        $transactionProvider = new LoanTransactionProvider($connection);
        $transactionService = new LoanTransactionService($transactionProvider);

        if ($transaction && count($transactionService->getById($input)) > 0) {
            return true;
        }
        return false;
    }

}