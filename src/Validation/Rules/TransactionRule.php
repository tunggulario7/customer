<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Modules\Transaction\Provider\TransactionProvider;
use App\Modules\Transaction\Service\TransactionService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class TransactionRule extends AbstractRule
{
    public function validate($input): bool
    {
        $input = (int) $input;
        $transaction = v::intType()->notEmpty()->validate($input);

        //Get Transaction Service
        $connection = new Connection();
        $transactionProvider = new TransactionProvider($connection);
        $transactionService = new TransactionService($transactionProvider);

        if ($transaction && count($transactionService->getById($input)) > 0) {
            return true;
        }
        return false;
    }

}