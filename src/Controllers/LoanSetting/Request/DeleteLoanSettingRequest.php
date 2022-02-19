<?php

namespace App\Controllers\LoanSetting\Request;

use App\Controllers\BaseRequest;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteLoanSettingRequest extends BaseRequest
{
    protected LoanSettingService $loanSettingService;
    public function __construct(LoanSettingService $loanSettingService)
    {
        $this->loanSettingService = $loanSettingService;
    }

    public function getResponse(): Response
    {
        $this->loanSettingService->delete($this->args);
        $this->response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
