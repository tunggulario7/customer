<?php

declare(strict_types=1);

namespace App\Controllers\LoanTransaction\Request;

use App\Controllers\BaseRequest;
use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\LoanTransaction\Service\LoanTransactionService;
use Psr\Http\Message\ResponseInterface as Response;

class GetLoanTransactionByCustomer extends BaseRequest
{
    protected LoanTransactionService $loanTransactionService;
    protected InstallmentService $installmentService;
    public function __construct(LoanTransactionService $loanTransactionService, InstallmentService $installmentService)
    {
        $this->loanTransactionService = $loanTransactionService;
        $this->installmentService = $installmentService;
    }

    public function getResponse(): Response
    {
        $returnBody = $this->loanTransactionService->getByCustomer($this->args['customerId']);
        for ($i = 0; $i < count($returnBody); $i++) {
            $returnBody[$i]['installment'] = $this->installmentService->getAllByLoanTransactionId($returnBody[$i]['loanId']);
        }
        $statusCode = 200;

        $this->response->getBody()->write(json_encode($returnBody));
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
