<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Factory\Connection;
use App\Modules\LoanSetting\Provider\LoanSettingProvider;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

class LoanPurposePeriodRule extends AbstractRule
{
    protected int $loanPurposeId;
    public function __construct($loanPurposeId)
    {
        $this->loanPurposeId = $loanPurposeId;
    }

    public function validate($input): bool
    {
        $input = (int) $input;
        $validate = v::intType()->between(1, 12)->notEmpty()->validate($input);

        //Get Loan Setting Data
        $connection = new Connection();
        $loanSettingProvider = new LoanSettingProvider($connection);
        $loanSettingService = new LoanSettingService($loanSettingProvider);

        $data = $loanSettingService->getByLoanPurposePeriod($this->loanPurposeId, $input);

        if ($validate && $data) {
            return true;
        }

        return false;
    }
}
