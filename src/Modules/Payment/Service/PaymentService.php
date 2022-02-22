<?php

declare(strict_types=1);

namespace App\Modules\Payment\Service;

use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\Payment\Model\PaymentFixCalculation;
use App\Modules\Payment\Model\Payment;

class PaymentService
{
    private InstallmentService $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * function Update LoanTransaction Detail Data
     * @param $data
     * @param $id
     * @return string
     */
    public function payment($loanTransactionId, $amount): array
    {
        $transactionDetailData = $this->installmentService->getAllByLoanTransactionIdNotPaid($loanTransactionId);

        $rows = [];
        foreach ($transactionDetailData as $data) {
            $fixedPayment = new PaymentFixCalculation();
            $fixedPayment->setTotalPay((int) $amount);
            $fixedPayment->setAmount((int) $data['underpayment']);
            $fixedPayment->setOverAmount((int) $data['payback']);
            $payment = (new Payment())
                ->setPaymentModel($fixedPayment, $this->installmentService)
                ->getCalculation();

            $amount = $payment['overPayment'];
            
            unset($payment['overPayment']);
            
            $this->installmentService->update($payment, $data);

            $payment['notice'] = "";
            if ($data['due_date'] < date("Y-m-d H:i:s")) {
                $payment['notice'] = "Your bill is past due";
            }

            $rows[] = $payment;

            if ($amount <= 0) {
                break;
            }
        }
        return $rows;
    }
}
