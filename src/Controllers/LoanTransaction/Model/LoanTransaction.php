<?php

declare(strict_types=1);

namespace App\Controllers\LoanTransaction\Model;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class LoanTransaction
{

    private int $customerId;
    private string $loanDate;
    private int $loanPurpose;
    private int $period;
    private int $loanAmount;

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId): void
    {
        try {
            $this->customerId = $customerId;
        } catch (\Throwable $e) {
            $this->customerId = 0;
        }
    }

    public function getLoanDate(): string
    {
        return $this->loanDate;
    }

    public function setLoanDate($loanDate): void
    {
        try {
            $this->loanDate = $loanDate;
        } catch (\Throwable $e) {
            $this->loanDate = '0';
        }
    }

    public function getLoanPurpose(): int
    {
        return $this->loanPurpose;
    }

    public function setLoanPurpose($loanPurpose): void
    {
        try {
            $this->loanPurpose = $loanPurpose;
        } catch (\Throwable $e) {
            $this->loanPurpose = 0;
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

    public function getLoanAmount(): int
    {
        return $this->loanAmount;
    }

    public function setLoanAmount($loanAmount): void
    {
        try {
            $this->loanAmount = $loanAmount;
        } catch (\Throwable $e) {
            $this->loanAmount = 0;
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

        $this->setCustomerId($request['customerId']);
        $this->setLoanDate($request['loanDate']);
        $this->setLoanPurpose($request['loanPurpose']);
        $this->setPeriod($request['period']);
        $this->setLoanAmount($request['loanAmount']);

        $customerValidator = v::attribute('customerId', v::CustomerRule())
            ->attribute('loanDate', v::date())
            ->attribute('loanPurpose', v::LoanPurposeRule())
            ->attribute('period', v::intType()->between(1, 12))
            ->attribute('loanAmount', v::number()->between(1000, 10000));

        $errorMessage = [];
        try {
            $customerValidator->assert($this);
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