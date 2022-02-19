<?php

declare(strict_types=1);

namespace App\Controllers\LoanSetting\Request;

use App\Controllers\BaseRequest;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllLoanSettingRequest extends BaseRequest
{
    protected LoanSettingService $loanSettingService;
    public function __construct(LoanSettingService $loanSettingService)
    {
        $this->loanSettingService = $loanSettingService;
    }

    public function getResponse(): Response
    {
        $data = $this->loanSettingService->getAll();
        $this->response->getBody()->write(json_encode($data));
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
