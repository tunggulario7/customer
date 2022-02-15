<?php

declare(strict_types=1);

namespace App\Controllers\LoanSetting\Request;

use App\Controllers\LoanSetting\Model\LoanSetting;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddLoanSettingRequest
{
    protected LoanSettingService $loanSettingService;
    protected LoanSetting $loanSettingModel;
    public function __construct(LoanSettingService $loanSettingService, LoanSetting $loanSettingModel)
    {
        $this->loanSettingService = $loanSettingService;
        $this->loanSettingModel = $loanSettingModel;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $validate = $this->loanSettingModel->validate($request->getParsedBody());

        if (empty($validate)) {
            $data = [
                'loanPurposeId' => $this->loanSettingModel->getLoanPurposeId(),
                'period' => $this->loanSettingModel->getPeriod()
            ];
            $id = $this->loanSettingService->insert($data);

            $returnBody = $this->loanSettingService->getById($id);
            $statusCode = 200;

            $returnBody = json_encode($returnBody);
        } else {
            $returnBody = json_encode($validate);
            $statusCode = 422;
        }

        $response->getBody()->write($returnBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
