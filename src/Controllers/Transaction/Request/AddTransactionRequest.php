<?php

declare(strict_types=1);

namespace App\Controllers\Transaction\Request;

use App\Controllers\Transaction\Model\Transaction;
use App\Modules\Installment\Model\FixedInstallment;
use App\Modules\Installment\Model\Installment;
use App\Modules\Transaction\Service\TransactionDetailService;
use App\Modules\Transaction\Service\TransactionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddTransactionRequest
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

    public function __invoke(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();
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

            $returnBody = json_encode($returnBody);

        } else {
            $returnBody = json_encode($validation);
            $statusCode = 422;
        }

        $response->getBody()->write($returnBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

}