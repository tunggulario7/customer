<?php

declare(strict_types=1);

namespace App\Modules\Payment\Model;

class PaymentFixCalculation implements PaymentInterface
{
    protected int $totalPay;
    public function setTotalPay(int $totalPay): void
    {
        $this->totalPay = $totalPay;
    }

    public function getTotalPay(): int
    {
        return $this->totalPay;
    }

    protected int $amount;
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    protected int $overAmount;
    public function setOverAmount(int $overAmount): void
    {
        $this->overAmount = $overAmount;
    }

    public function getOverAmount(): int
    {
        return $this->overAmount;
    }

    protected float $payback;
    protected float $underPayment;
    protected float $overPayment;
    protected int $flagPaid;

    public function calculate(): void
    {
        if ($this->getTotalPay() >= $this->getAmount()) {
            $this->payback = $this->getAmount() + $this->getOverAmount();
            $this->underPayment = 0;
            $this->overPayment = $this->getTotalPay() - $this->getAmount();
            $this->flagPaid = 1;
        } else {
            $this->payback = $this->getTotalPay() + $this->getOverAmount();
            $this->underPayment = abs($this->getTotalPay() - $this->getAmount());
            $this->overPayment = 0;
            $this->flagPaid = 0;
        }
    }

    public function getPayback(): float
    {
        return $this->payback;
    }

    public function getUnderPayment(): float
    {
        return $this->underPayment;
    }

    public function getOverPayment(): float
    {
        return $this->overPayment;
    }

    public function getFlagPaid(): int
    {
        return $this->flagPaid;
    }
}
