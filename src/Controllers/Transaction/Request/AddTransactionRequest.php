<?php

declare(strict_types=1);

namespace App\Controllers\Transaction\Request;

use App\Controllers\BaseRequest;
use App\Controllers\Transaction\Model\Transaction;
use App\Modules\Installment\Model\FixedInstallment;
use App\Modules\Installment\Model\Installment;
use App\Modules\Transaction\Service\TransactionDetailService;
use App\Modules\Transaction\Service\TransactionService;
use Psr\Http\Message\ResponseInterface as Response;

class AddTransactionRequest extends BaseRequest
{
    protected TransactionService $transactionService;
    protected TransactionDetailService $transactionDetailService;
    protected Transaction $transactionModel;
    public function __construct(TransactionService $transactionService, TransactionDetailService $transactionDetailService, Transaction $transactionModel)
    {
        $this->transactionService = $transactionService;
        $this->transactionDetailService = $transactionDetailService;
        $this->transactionModel = $transactionModel;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getParsedBody();
        $validation = $this->transactionModel->validate($requestBody);

        if (empty($validation)) {
            //Create Transaction
            $data = [
                'customerId' => $this->transactionModel->getCustomerId(),
                'transactionDate' => $this->transactionModel->getTransactionDate(),
                'loanPurpose' => $this->transactionModel->getLoanPurpose(),
                'loanPeriod' => $this->transactionModel->getPeriod(),
                'loanAmount' => $this->transactionModel->getLoanAmount()
            ];
            $id = $this->transactionService->insert($data);


            //Create Detail Transaction
            $fixedInstallment = new FixedInstallment();
            $fixedInstallment->setLoanDate($this->transactionModel->getTransactionDate());
            $fixedInstallment->setLoanAmount((int) $this->transactionModel->getLoanAmount());
            $fixedInstallment->setPeriod((int) $this->transactionModel->getPeriod());
            $installment = (new Installment())
                ->setInstallmentModel($fixedInstallment)
                ->getInstallments();

            $this->transactionDetailService->insert($installment, $id);

            $returnBody = $this->transactionService->getById($id);
            $returnBody['installment'] = $this->transactionDetailService->getAllByTransactionId($id);
            $statusCode = 200;
        } else {
            $returnBody = $validation;
            $statusCode = 422;
        }

        $this->response->getBody()->write(json_encode($returnBody));
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
