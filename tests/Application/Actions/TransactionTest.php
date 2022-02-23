<?php

namespace Tests\Application\Actions;

use App\Controllers\LoanTransaction\Model\LoanTransaction;
use App\Controllers\LoanTransaction\Request\AddLoanTransactionRequest;
use App\Controllers\LoanTransaction\Request\GetLoanTransactionByCustomer;
use App\Factory\Connection;
use App\Modules\LoanTransaction\Provider\InstallmentProvider;
use App\Modules\LoanTransaction\Provider\LoanTransactionProvider;
use App\Modules\LoanTransaction\Service\InstallmentService;
use App\Modules\LoanTransaction\Service\LoanTransactionService;
use Slim\Psr7\Response;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    public function setResponse(): Response
    {
        return new Response();
    }

    public function setLoanTransactionModel(): LoanTransaction
    {
        return new LoanTransaction();
    }

    public function setLoanTransactionService() :LoanTransactionService
    {
        $connection = new Connection();
        $loanTransactionProvider = new LoanTransactionProvider($connection);
        return new LoanTransactionService($loanTransactionProvider);
    }

    public function setInstallmentService(): InstallmentService
    {
        $connection = new Connection();
        $installmentProvider = new InstallmentProvider($connection);
        return new InstallmentService($installmentProvider);
    }

    public function testInsertDataProvider(): array
    {
        return [
            [
                [
                    'customerId' => 3,
                    'loanDate' => "2022-02-24",
                    'loanPurpose' => 3,
                    'period' => 6,
                    'loanAmount' => 10000,
                ], 200
            ],
        ];
    }

    public function testNegativeInsertDataProvider(): array
    {
        return [
            [
                [
                    'customerId' => "6",
                    'loanDate' => "2022-02-24",
                    'loanPurpose' => 3,
                    'period' => 6,
                    'loanAmount' => 10000,
                ], 422,
                '[
                    {
                        "status": "customerId",
                        "message": "Failed Validation",
                        "errors": "customerId must be valid"
                    }
                ]'
            ],
        ];
    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/transaction', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addLoanTransactionRequest = new AddLoanTransactionRequest($this->setLoanTransactionService(), $this->setInstallmentService(), $this->setLoanTransactionModel());
        $responseData = $addLoanTransactionRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addLoanTransactionRequest = new AddLoanTransactionRequest($this->setLoanTransactionService(), $this->setInstallmentService(), $this->setLoanTransactionModel());
        $responseData = $addLoanTransactionRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }

    public function testGetData()
    {
        //Define Json Body
        $requestBody = $this->createRequest('GET', '/transaction/customer', ['Content-Type' => 'application/json']);

        $addCustomerRequest = new GetLoanTransactionByCustomer($this->setLoanTransactionService(), $this->setInstallmentService());
        $responseData = $addCustomerRequest->__invoke($requestBody, $this->setResponse(), ['customerId' => 3]);

        $this->assertEquals(200, $responseData->getStatusCode());
    }
}