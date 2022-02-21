<?php

declare(strict_types=1);

namespace App\Modules\Payment\Model;
use App\Modules\Transaction\Service\TransactionDetailService;

class Payment
{
    protected PaymentInterface $paymentModel;
    protected TransactionDetailService $transactionDetailService;
    public function setPaymentModel(PaymentInterface $paymentModel, TransactionDetailService $transactionDetailService): self
    {
        $this->paymentModel = $paymentModel;
        $this->transactionDetailService = $transactionDetailService;
        return $this;
    }

    public function getCalculation(): array
    {
        $this->paymentModel->calculate();

        $dateNow = date("Y-m-d H:i:s");
        $data = [
            'payback_date' => $dateNow,
            'payback' => $this->paymentModel->getPayback(),
            'underpayment' => $this->paymentModel->getUnderPayment(),
            'overPayment' => $this->paymentModel->getOverPayment(),
            'paid' => $this->paymentModel->getFlagPaid()
        ];

        return $data;
    }
}
