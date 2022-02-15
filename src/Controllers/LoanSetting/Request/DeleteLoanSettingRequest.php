<?php

namespace App\Controllers\LoanSetting\Request;

use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteLoanSettingRequest
{
    protected LoanSettingService $loanSettingService;
    public function __construct(LoanSettingService $loanSettingService)
    {
        $this->loanSettingService = $loanSettingService;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->loanSettingService->delete($args);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
