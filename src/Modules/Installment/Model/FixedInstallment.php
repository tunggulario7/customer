<?php

declare(strict_types=1);

namespace App\Modules\Installment\Model;

class FixedInstallment implements InstallmentInterface
{
    protected int $loanAmount;
    public function setLoanAmount(int $loanAmount): void
    {
        $this->loanAmount = $loanAmount;
    }

    public function getLoanAmount(): int
    {
        return $this->loanAmount;
    }

    protected int $period;
    public function setPeriod(int $period): void
    {
        $this->period = $period;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    protected string $loanDate;
    public function setLoanDate(string $loanDate): void
    {
        $this->loanDate = $loanDate;
    }

    public function getLoanDate(): string
    {
        return $this->loanDate;
    }

    protected float $amount;
    public function calculate(): void
    {
        $this->amount = round($this->getLoanAmount() / $this->getPeriod());
    }

    public function getAmount(): float
    {
        var_dump($this->amount);
        return $this->amount;
    }
}