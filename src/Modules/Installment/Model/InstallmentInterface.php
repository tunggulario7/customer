<?php

declare(strict_types=1);

namespace App\Modules\Installment\Model;

interface InstallmentInterface
{
    public function calculate(): void;

    public function getAmount(): float;

    public function setLoanAmount(int $loanAmount): void;

    public function setPeriod(int $period): void;

    public function setLoanDate(string $loanDate): void;

}