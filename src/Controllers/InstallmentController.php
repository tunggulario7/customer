<?php

namespace App\Controllers;

use App\Factory\Connection;
use App\Services\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InstallmentController
{
    public function calculation(Request $request, Response $response)
    {
        $requestBody = $request->getQueryParams();

        $connection = new Connection();
        $loanSetting = new LoanSettingService($connection);
        $loanSettingData = $loanSetting->getByLoanPurpose($requestBody['loanPurpose']);

        $responseData = [];

        foreach ($loanSettingData as $data) {
            $installment = round($requestBody['loanAmount'] / $data['period']);

            $installmentData = [];
            for ($i = 1; $i <= $data['period']; $i++) {
                $period = $i * 30;
                $datePeriod = '+' . $period . ' days';

                $dueDate = date('Y-m-d', strtotime($datePeriod, strtotime($requestBody['loanDate'])));

                $installmentData[] = [
                    'period' => $i,
                    'dueDate' => $dueDate,
                    'installment' => $installment
                ];
            }
            $responseData[] = [
                'loanPurpose' => $data['loanPurpose'],
                'installmentPeriod' => $data['period'],
                'installment' => $installmentData
            ];
        }

        $response->getBody()->write(json_encode($responseData));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
