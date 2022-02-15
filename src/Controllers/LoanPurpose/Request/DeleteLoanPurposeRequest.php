<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Modules\LoanPurpose\Service\LoanPurposeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteLoanPurposeRequest
{
    protected LoanPurposeService $loanPurposeService;
    public function __construct(LoanPurposeService $loanPurposeService)
    {
        $this->loanPurposeService = $loanPurposeService;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->loanPurposeService->delete($args);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}