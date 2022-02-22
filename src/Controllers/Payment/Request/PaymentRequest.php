<?php

declare(strict_types=1);

namespace App\Controllers\Payment\Request;

use App\Controllers\BaseRequest;
use App\Controllers\Payment\Model\Payment;
use App\Modules\LoanTransaction\Service\InstallmentService;
use Psr\Http\Message\ResponseInterface as Response;
use App\Modules\Payment\Service\PaymentService;

class PaymentRequest extends BaseRequest
{
    protected InstallmentService $installmentService;
    protected Payment $paymentModel;
    protected PaymentService $paymentService;
    public function __construct(InstallmentService $installmentService, Payment $paymentModel, PaymentService $paymentService)
    {
        $this->installmentService = $installmentService;
        $this->paymentModel = $paymentModel;
        $this->paymentService = $paymentService;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getParsedBody();
        $validate = $this->paymentModel->validate($requestBody);

        if (empty($validate)) {
            $returnBody = $this->paymentService->payment($requestBody['loanTransactionId'], $requestBody['totalPay']);
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