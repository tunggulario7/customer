<?php

declare(strict_types=1);

namespace App\Controllers\Payment\Request;

use App\Controllers\BaseRequest;
use App\Controllers\Payment\Model\Payment;
use App\Modules\Transaction\Service\TransactionDetailService;
use Psr\Http\Message\ResponseInterface as Response;
use App\Modules\Payment\Service\PaymentService;

class PaymentRequest extends BaseRequest
{
    protected TransactionDetailService $transactionDetailService;
    protected Payment $paymentModel;
    protected PaymentService $paymentService;
    public function __construct(TransactionDetailService $transactionDetailService, Payment $paymentModel, PaymentService $paymentService)
    {
        $this->transactionDetailService = $transactionDetailService;
        $this->paymentModel = $paymentModel;
        $this->paymentService = $paymentService;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getParsedBody();
        $validate = $this->paymentModel->validate($requestBody);

        if (empty($validate)) {
            $this->paymentService->payment($requestBody['transactionId'], $requestBody['totalPay']);

            $returnBody = $this->transactionDetailService->getAllByTransactionId($requestBody['transactionId']);
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