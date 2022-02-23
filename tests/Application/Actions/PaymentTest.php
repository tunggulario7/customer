<?php

namespace Tests\Application\Actions;

use App\Controllers\Payment\Model\Payment;
use App\Controllers\Payment\Request\PaymentRequest;
use App\Factory\Connection;
use App\Modules\LoanTransaction\Provider\InstallmentProvider;
use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\Payment\Provider\PaymentProvider;
use App\Modules\Payment\Service\PaymentService;
use Slim\Psr7\Response;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function setResponse(): Response
    {
        return new Response();
    }

    public function setInstallmentService(): InstallmentService
    {
        $connection = new Connection();
        $installmentProvoder = new InstallmentProvider($connection);
        return new InstallmentService($installmentProvoder);
    }

    public function setPaymentModel(): Payment
    {
        return new Payment();
    }

    public function setPaymentService(): PaymentService
    {
        $connection = new Connection();
        $paymentProvider = new PaymentProvider($connection);

        $installmentProvider = new InstallmentProvider($connection);
        $installmentService = new InstallmentService($installmentProvider);
        return new PaymentService($installmentService, $paymentProvider);
    }

    public function testInsertDataProvider(): array
    {
        return [
            [
                [
                    'loanTransactionId' => 8,
                    'totalPay' => 1700
                ], 200
            ],
        ];
    }

    public function testNegativeInsertDataProvider(): array
    {
        return [
            [
                [
                    'loanTransactionId' => 4,
                    'totalPay' => 1700
                ], 422,
                '[
                    {
                        "status": "loanTransactionId",
                        "message": "Failed Validation",
                        "errors": "loanTransactionId must be valid"
                    }
                ]'
            ],
        ];
    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/payment', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $paymentRequest = new PaymentRequest($this->setInstallmentService(), $this->setPaymentModel(), $this->setPaymentService());
        $responseData = $paymentRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $paymentRequest = new PaymentRequest($this->setInstallmentService(), $this->setPaymentModel(), $this->setPaymentService());
        $responseData = $paymentRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }
}
