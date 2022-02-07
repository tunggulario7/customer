<?php

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Services\CustomerService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class CustomerRule extends AbstractRule
{
    public function validate($input): bool
    {
        $input = (int) $input;
        $customer = v::intType()->notEmpty()->validate($input);

        //Get Customer Service
        $connection = new Connection();
        $customerService = new CustomerService($connection);

        if ($customer && count($customerService->getById($input)) > 0) {
            return true;
        }
        return false;
    }

}