<?php

declare(strict_types=1);

namespace App\Controllers\Installment\Request;

use App\Controllers\BaseRequest;
use App\Modules\Installment\Model\FixedInstallment;
use App\Modules\Installment\Model\Installment;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;

class InstallmentRequest extends BaseRequest
{
    protected LoanSettingService $loanSettingService;
    public function __construct(LoanSettingService $loanSettingService)
    {
        $this->loanSettingService = $loanSettingService;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getQueryParams();
        $loanSettingData = $this->loanSettingService->getByLoanPurpose($requestBody['loanPurpose']);

        $responseData = [];

        foreach ($loanSettingData as $data) {
            $fixedInstallment = new FixedInstallment();
            $fixedInstallment->setLoanDate($requestBody['loanDate']);
            $fixedInstallment->setLoanAmount((int) $requestBody['loanAmount']);
            $fixedInstallment->setPeriod((int) $data['period']);
            $installment = (new Installment())
                ->setInstallmentModel($fixedInstallment)
                ->getInstallments();
            $responseData[] = [
                'loanPurpose' => $data['loanPurpose'],
                'installmentPeriod' => $data['period'],
                'installment' => $installment
            ];
        }

        $this->response->getBody()->write(json_encode($responseData));
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}