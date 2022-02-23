<?php

namespace Tests\Application\Actions;

use App\Controllers\LoanTransaction\Model\LoanTransaction;
use App\Controllers\LoanTransaction\Request\AddLoanTransactionRequest;
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
                    'customerId' => "3",
                    'loanDate' => "2022-02-07",
                    'loanPurpose' => 3,
                    'period' => 6,
                    'loanAmount' => 10000,
                ], 200,
                '{
                    "loanDate": "2022-02-23 00:00:00",
                    "name": "Tunggul Ario S",
                    "ktp": "1234562312923456",
                    "dateOfBirth": "1992-12-23",
                    "loanPurpose": "Wedding",
                    "installment": [
                        {
                            "id": "16",
                            "loan_transaction_id": "5",
                            "month": "1",
                            "due_date": "2022-03-25",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        },
                        {
                            "id": "17",
                            "loan_transaction_id": "5",
                            "month": "2",
                            "due_date": "2022-04-24",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        },
                        {
                            "id": "18",
                            "loan_transaction_id": "5",
                            "month": "3",
                            "due_date": "2022-05-24",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        },
                        {
                            "id": "19",
                            "loan_transaction_id": "5",
                            "month": "4",
                            "due_date": "2022-06-23",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        },
                        {
                            "id": "20",
                            "loan_transaction_id": "5",
                            "month": "5",
                            "due_date": "2022-07-23",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        },
                        {
                            "id": "21",
                            "loan_transaction_id": "5",
                            "month": "6",
                            "due_date": "2022-08-22",
                            "payback_date": null,
                            "amount": "1667",
                            "payback": null,
                            "underpayment": "1667",
                            "paid": "0",
                            "created_at": "2022-02-23 10:08:45",
                            "updated_at": null
                        }
                    ]
                }'
            ],
        ];
    }

    public function testNegativeInsertDataProvider(): array
    {
        return [
            [
                [
                    'customerId' => "6",
                    'loanDate' => "2022-02-07",
                    'loanPurpose' => 3,
                    'period' => 6,
                    'loanAmount' => 10000,
                ], 422,
                'customerId must be valid'
            ],
        ];
    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/transaction', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addLoanTransactionRequest = new AddLoanTransactionRequest($this->setLoanTransactionService(), $this->setInstallmentService(), $this->setLoanTransactionModel());
        $responseData = $addLoanTransactionRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
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
}