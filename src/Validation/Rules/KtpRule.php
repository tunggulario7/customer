<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Services\CustomerService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class KtpRule extends AbstractRule
{
    private string $dateOfBirth;
    private string $sex;
    private int $customerId;

    public function __construct($dateOfBirth, $sex, $customerId)
    {
        $this->dateOfBirth = $dateOfBirth;
        $this->sex = $sex;
        $this->customerId = $customerId;
    }

    public function validate($input): bool
    {
        //Get Data KTP from Customer Table
        $connection = new Connection();
        $customerService = new CustomerService($connection);

        $input = (string) $input;
        $number = v::number()->notEmpty()->length(16, 16)->validate($input);

        //Check Condition for Format KTP XXXXXXDDMMYYXXXX
        $subKtp = substr($input, 6, 6);

        $dateCreate = date_create($this->dateOfBirth);
        $dateFormat = date_format($dateCreate, "dmy");

        //Filter By Status Create or Update
        if ($this->customerId == 0) {
            $customerData = $customerService->getByField('ktp', $input);
            $customerDataFilter = count($customerData) == 0;
        } else {
            $customerData = $customerService->getByFieldWithId('ktp', $input, $this->customerId);
            $customerDataFilter = count($customerData) > 0;
        }

        if ($number && $customerDataFilter && $this->sex == 'M' && $dateFormat == $subKtp) {
            return true;
        } elseif ($number && $customerDataFilter && $this->sex == 'F' && ($dateFormat + 400000) == $subKtp) {
            return true;
        }

        return false;
    }
}
