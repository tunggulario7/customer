<?php

namespace Tests\Application\Actions\Customer;

use Tests\TestCase;
use App\Controllers\CustomerController;

class CustomerTest extends TestCase
{
    public function testInsertDataProvider(): array
    {
        return [
            [
                [
                    'name' => "Lorem Ipsum",
                    'ktp' => 1234562401003456,
                    'dateOfBirth' => "2000-01-24",
                    'sex' => "M",
                    'address' => "Jalan Sudirman",
                ], 200,
                '{
                    "name": "Lorem Ipsum",
                    "ktp": 1234562401003456,
                    "dateOfBirth": "2000-01-24",
                    "sex": "M",
                    "address": "Jalan Sudirman",
                    "id": "3"
                }'
            ],
        ];
    }

    public function testNegativeInsertDataProvider(): array
    {
        return [
            [
                [
                    'name' => "Lorem Ipsum",
                    'ktp' => 1234562401003417,
                    'dateOfBirth' => "2000-01-24",
                    'sex' => "M",
                    'address' => "Jalan Sudirman",
                ], 422,
                'ktp must be valid'
            ],
        ];
    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $response = new \Slim\Psr7\Response();

        $customer = new CustomerController();
        $responseData = $customer->insert($json, $response);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), $responseArray);
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $response = new \Slim\Psr7\Response();

        $customer = new CustomerController();
        $responseData = $customer->insert($json, $response);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals((array) ($expectedResponse), $responseArray);
    }

    /** @dataProvider testInsertDataProvider */
    public function testUpdateData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('PUT', '/customer/3', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $response = new \Slim\Psr7\Response();

        $customer = new CustomerController();
        $responseData = $customer->update($json, $response, ['id' => 3]);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), $responseArray);
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeUpdateData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('PUT', '/customer/3', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $response = new \Slim\Psr7\Response();

        $customer = new CustomerController();
        $responseData = $customer->update($json, $response, ['id' => 3]);

        $responseArray = json_decode($responseData->getBody(), true);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals((array) ($expectedResponse), $responseArray);
    }

    public function testGetData()
    {
        //Define Json Body
        $requestBody = $this->createRequest('GET', '/customer', ['Content-Type' => 'application/json']);

        $response = new \Slim\Psr7\Response();

        $customer = new CustomerController();
        $responseData = $customer->getAll($requestBody, $response);

        $this->assertEquals(200, $responseData->getStatusCode());
    }
}
