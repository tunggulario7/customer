<?php

declare(strict_types=1);

namespace App\Modules\Payment\Model;

interface PaymentInterface
{
    public function calculate(): void;

    public function getPayback(): float;

    public function getUnderPayment(): float;

    public function getOverPayment(): float;

    public function getFlagPaid(): int;

    public function setAmount(int $amount): void;

    public function setTotalPay(int $totalPay): void;

    public function setOverAmount(int $overAmount): void;

}