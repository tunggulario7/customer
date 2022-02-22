<?php

declare(strict_types=1);

namespace App\Modules\Payment\Model;
use App\Modules\LoanTransaction\Service\InstallmentService;

class Payment
{
    protected PaymentInterface $paymentModel;
    protected InstallmentService $installmentService;
    public function setPaymentModel(PaymentInterface $paymentModel, InstallmentService $installmentService): self
    {
        $this->paymentModel = $paymentModel;
        $this->installmentService = $installmentService;
        return $this;
    }

    public function getCalculation(): array
    {
        $this->paymentModel->calculate();

        $dateNow = date("Y-m-d H:i:s");
        return [
            'payback_date' => $dateNow,
            'payback' => $this->paymentModel->getPayback(),
            'underpayment' => $this->paymentModel->getUnderPayment(),
            'overPayment' => $this->paymentModel->getOverPayment(),
            'paid' => $this->paymentModel->getFlagPaid()
        ];
    }
}
