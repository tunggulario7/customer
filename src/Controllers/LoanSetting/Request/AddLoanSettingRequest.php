<?php

declare(strict_types=1);

namespace App\Controllers\LoanSetting\Request;

use App\Controllers\BaseRequest;
use App\Controllers\LoanSetting\Model\LoanSetting;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;

class AddLoanSettingRequest extends BaseRequest
{
    protected LoanSettingService $loanSettingService;
    protected LoanSetting $loanSettingModel;
    public function __construct(LoanSettingService $loanSettingService, LoanSetting $loanSettingModel)
    {
        $this->loanSettingService = $loanSettingService;
        $this->loanSettingModel = $loanSettingModel;
    }

    public function getResponse(): Response
    {
        $validate = $this->loanSettingModel->validate($this->request->getParsedBody());

        if (empty($validate)) {
            $data = [
                'loanPurposeId' => $this->loanSettingModel->getLoanPurposeId(),
                'period' => $this->loanSettingModel->getPeriod()
            ];
            $id = $this->loanSettingService->insert($data);

            $returnBody = $this->loanSettingService->getById($id);
            $statusCode = 200;
        } else {
            $returnBody = $validate;
            $statusCode = 422;
        }

        $this->response->getBody()->write(json_encode($returnBody));
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
