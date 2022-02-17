<?php

declare(strict_types=1);

namespace App\Controllers\Installment\Request;

use App\Modules\Installment\Model\FixedInstallment;
use App\Modules\Installment\Model\Installment;
use App\Modules\Installment\Model\InstallmentInterface;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InstallmentRequest
{
    protected LoanSettingService $loanSettingService;
    public function __construct(LoanSettingService $loanSettingService)
    {
        $this->loanSettingService = $loanSettingService;
    }

    protected InstallmentInterface $installment;
    public function setInstallmentModel(InstallmentInterface $installment): self
    {
        $this->installment = $installment;
        return $this;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $requestBody = $request->getQueryParams();
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

        $response->getBody()->write(json_encode($responseData));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}