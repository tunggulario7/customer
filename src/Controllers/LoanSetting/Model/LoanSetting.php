<?php

declare(strict_types=1);

namespace App\Controllers\LoanSetting\Model;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class LoanSetting
{

    private int $loanPurposeId;
    private int $period;

    public function getLoanPurposeId(): int
    {
        return $this->loanPurposeId;
    }

    public function setLoanPurposeId($loanPurposeId): void
    {
        try {
            $this->loanPurposeId = $loanPurposeId;
        } catch (\Throwable $e) {
            $this->loanPurposeId = 0;
        }
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function setPeriod($period): void
    {
        try {
            $this->period = $period;
        } catch (\Throwable $e) {
            $this->period = 0;
        }
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
            foreach ($messages as $key => $message) {
                $errorMessage[] = [
                    "status" => $key,
                    "message" => "Failed Validation",
                    "errors" => $message
                ];
            }
        }
        return $errorMessage;
    }

}