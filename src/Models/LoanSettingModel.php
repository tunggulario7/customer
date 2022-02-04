<?php

declare(strict_types=1);

namespace App\Models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class LoanSettingModel
{

    private int $loanPurposeId;
    private int $period;

    public function getLoanPurposeId(): int
    {
        return $this->loanPurposeId;
    }

    public function setLoanPurposeId($loanPurposeId): void
    {
        $this->loanPurposeId = $loanPurposeId;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function setPeriod($period): void
    {
        $this->period = $period;
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request)
    {
        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Validation\\Rules')
                ->withExceptionNamespace('App\\Validation\\Exceptions')
        );

        $this->setLoanPurposeId($request['loanPurposeId']);
        $this->setPeriod($request['period']);

        $validate = v::attribute('loanPurposeId', v::LoanPurposeRule())
            ->attribute('period', v::intType()->between(1, 12));

        $errorMessage = [];
        try {
            $validate->assert($this);
        } catch (NestedValidationException $ex) {
            $messages = $ex->getMessages();
            foreach ($messages as $message) {
                $errorMessage[] = $message;
            }
        }
        return $errorMessage;
    }

}