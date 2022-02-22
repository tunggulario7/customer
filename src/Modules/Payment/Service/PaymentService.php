<?php

declare(strict_types=1);

namespace App\Modules\Payment\Service;

use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\Payment\Model\PaymentFixCalculation;
use App\Modules\Payment\Model\Payment;
use App\Modules\Payment\Provider\PaymentProvider;

class PaymentService
{
    protected InstallmentService $installmentService;
    protected PaymentProvider $paymentProvider;

    public function __construct(InstallmentService $installmentService, PaymentProvider $paymentProvider)
    {
        $this->installmentService = $installmentService;
        $this->paymentProvider = $paymentProvider;
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

            //Set For payments field insert
            $field = "installment_id, payback, underpayment, created_at";
            $value = ":installment_id, :payback, :underpayment, :created_at";

            $params = [
                [
                    "field" => ":installment_id",
                    "value" => $data['id']
                ],
                [
                    "field" => ":payback",
                    "value" => $payment['payback'] - $data['payback']
                ],
                [
                    "field" => ":underpayment",
                    "value" => $payment['underpayment']
                ],
                [
                    "field" => ":created_at",
                    "value" => date("Y-m-d H:i:s")
                ]
            ];
            $this->paymentProvider->insert($field, $value, $params);

            //Update Data Installment Table
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
