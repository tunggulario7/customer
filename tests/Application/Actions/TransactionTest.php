<?php

namespace Tests\Application\Actions\Customer;

use App\Controllers\TransactionController;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function testInsertDataProvider(): array
    {
        return [
            [
                [
                    'customerId' => "3",
                    'transactionDate' => "2022-02-07",
                    'loanPurpose' => 3,
                    'period' => 6,
                    'loanAmount' => 10000,
                ], 200,
                '{
                    "transactionDate": "2022-02-07 00:00:00",
                    "name": "Lorem Ipsum",
                    "ktp": "1234562312923456",
                    "dateOfBirth": "2000-01-24",
                    "loanPurpose": "Wedding",
                    "installment": [
                        {
                            "id": "31",
                            "transaction_id": "18",
                            "month": "1",
                            "due_date": "2022-03-09",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:11",
                            "updated_at": null
                        },
                        {
                            "id": "32",
                            "transaction_id": "18",
                            "month": "2",
                            "due_date": "2022-04-08",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:11",
                            "updated_at": null
                        },
                        {
                            "id": "33",
                            "transaction_id": "18",
                            "month": "3",
                            "due_date": "2022-05-08",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:12",
                            "updated_at": null
                        },
                        {
                            "id": "34",
                            "transaction_id": "18",
                            "month": "4",
                            "due_date": "2022-06-07",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:12",
                            "updated_at": null
                        },
                        {
                            "id": "35",
                            "transaction_id": "18",
                            "month": "5",
                            "due_date": "2022-07-07",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:12",
                            "updated_at": null
                        },
                        {
                            "id": "36",
                            "transaction_id": "18",
                            "month": "6",
                            "due_date": "2022-08-06",
                            "amount": "1667",
                            "paid": "0",
                            "created_at": "2022-02-07 22:44:12",
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
                    'transactionDate' => "2022-02-07",
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

        $response = new \Slim\Psr7\Response();

        $customer = new TransactionController();
        $responseData = $customer->insert($json, $response);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $response = new \Slim\Psr7\Response();

        $customer = new TransactionController();
        $responseData = $customer->insert($json, $response);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals((array) ($expectedResponse), $responseArray);
    }
}