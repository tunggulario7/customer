<?php

namespace App\Models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class TransactionModel
{

    private int $customerId;
    private string $transactionDate;
    private int $loanPurpose;
    private int $period;
    private int $loanAmount;

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getTransactionDate(): string
    {
        return $this->transactionDate;
    }

    public function setTransactionDate($transactionDate): void
    {
        $this->transactionDate = $transactionDate;
    }

    public function getLoanPurpose(): int
    {
        return $this->loanPurpose;
    }

    public function setLoanPurpose($loanPurpose): void
    {
        $this->loanPurpose = $loanPurpose;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function setPeriod($period): void
    {
        $this->period = $period;
    }

    public function getLoanAmount(): int
    {
        return $this->loanAmount;
    }

    public function setLoanAmount($loanAmount): void
    {
        $this->loanAmount = $loanAmount;
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

        $this->setCustomerId($request['customerId']);
        $this->setTransactionDate($request['transactionDate']);
        $this->setLoanPurpose($request['loanPurpose']);
        $this->setPeriod($request['period']);
        $this->setLoanAmount($request['loanAmount']);

        $customerValidator = v::attribute('customerId', v::CustomerRule())
            ->attribute('transactionDate', v::date())
            ->attribute('loanPurpose', v::LoanPurposeRule())
            ->attribute('period', v::intType()->between(1, 12))
            ->attribute('loanAmount', v::number()->between(1000, 10000));

        $errorMessage = [];
        try {
            $customerValidator->assert($this);
        } catch (NestedValidationException $ex) {
            $messages = $ex->getMessages();
            foreach ($messages as $message) {
                $errorMessage[] = $message;
            }
        }
        return $errorMessage;
    }

}