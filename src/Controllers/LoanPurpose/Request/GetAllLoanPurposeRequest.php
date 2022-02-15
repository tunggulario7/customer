<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Modules\LoanPurpose\Service\LoanPurposeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetAllLoanPurposeRequest
{
    protected LoanPurposeService $loanPurposeService;
    public function __construct(LoanPurposeService $loanPurposeService)
    {
        $this->loanPurposeService = $loanPurposeService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $this->loanPurposeService->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
