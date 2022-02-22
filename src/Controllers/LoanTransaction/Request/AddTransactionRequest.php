<?php

declare(strict_types=1);

namespace App\Controllers\LoanTransaction\Request;

use App\Controllers\BaseRequest;
use App\Controllers\LoanTransaction\Model\LoanTransaction;
use App\Modules\Installment\Model\FixedInstallment;
use App\Modules\Installment\Model\Installment;
use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\LoanTransaction\Service\LoanTransactionService;
use Psr\Http\Message\ResponseInterface as Response;

class AddTransactionRequest extends BaseRequest
{
    protected LoanTransactionService $loanTransactionService;
    protected InstallmentService $installmentService;
    protected LoanTransaction $loanTransactionModel;
    public function __construct(LoanTransactionService $loanTransactionService, InstallmentService $installmentService, LoanTransaction $loanTransactionModel)
    {
        $this->loanTransactionService = $loanTransactionService;
        $this->installmentService = $installmentService;
        $this->loanTransactionModel = $loanTransactionModel;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getParsedBody();
        $validation = $this->loanTransactionModel->validate($requestBody);

        if (empty($validation)) {
            //Create Loan Transaction
            $data = [
                'customerId' => $this->loanTransactionModel->getCustomerId(),
                'loanDate' => $this->loanTransactionModel->getLoanDate(),
                'loanPurpose' => $this->loanTransactionModel->getLoanPurpose(),
                'loanPeriod' => $this->loanTransactionModel->getPeriod(),
                'loanAmount' => $this->loanTransactionModel->getLoanAmount()
            ];
            $id = $this->loanTransactionService->insert($data);


            //Create Installment
            $fixedInstallment = new FixedInstallment();
            $fixedInstallment->setLoanDate($this->loanTransactionModel->getLoanDate());
            $fixedInstallment->setLoanAmount((int) $this->loanTransactionModel->getLoanAmount());
            $fixedInstallment->setPeriod((int) $this->loanTransactionModel->getPeriod());
            $installment = (new Installment())
                ->setInstallmentModel($fixedInstallment)
                ->getInstallments();

            $this->installmentService->insert($installment, $id);

            $returnBody = $this->loanTransactionService->getById($id);
            $returnBody['installment'] = $this->installmentService->getAllByLoanTransactionId($id);
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
