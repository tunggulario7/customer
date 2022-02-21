<?php

declare(strict_types=1);

namespace App\Modules\Payment\Service;


use App\Modules\Transaction\Service\TransactionDetailService;
use App\Modules\Payment\Model\PaymentFixCalculation;
use App\Modules\Payment\Model\Payment;

class PaymentService
{
    private TransactionDetailService $transactionDetailService;

    public function __construct(TransactionDetailService $transactionDetailService)
    {
        $this->transactionDetailService = $transactionDetailService;
    }

    /**
     * function Update Transaction Detail Data
     * @param $data
     * @param $id
     * @return string
     */
    public function payment($transactionId, $amount): int
    {
        $transactionDetailData = $this->transactionDetailService->getAllByTransactionIdNotPaid($transactionId);

        foreach ($transactionDetailData as $data) {
            $fixedPayment = new PaymentFixCalculation();
            $fixedPayment->setTotalPay((int) $amount);
            $fixedPayment->setAmount((int) $data['underpayment']);
            $payment = (new Payment())
                ->setPaymentModel($fixedPayment, $this->transactionDetailService)
                ->getCalculation();

            $amount = $payment['overPayment'];

            // var_dump($payment['overPayment']);
            
            unset($payment['overPayment']);
            
            // var_dump($payment);
            $this->transactionDetailService->update($payment, $data);

            // var_dump($amount);
            if ($amount <= 0) {
                break;
            }
        }

        return $transactionId;
    }
}
