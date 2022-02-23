<?php

namespace Tests\Application\Actions;

use App\Controllers\Customer\Model\Customer;
use App\Controllers\Customer\Request\AddCustomerRequest;
use App\Controllers\Customer\Request\GetAllCustomerRequest;
use App\Controllers\Customer\Request\UpdateCustomerRequest;
use App\Factory\Connection;
use App\Modules\Customer\Provider\CustomerProvider;
use App\Modules\Customer\Service\CustomerService;
use Slim\Psr7\Response;
use Tests\TestCase;

class CustomerTest extends TestCase
{

    public function setResponse(): Response
    {
        return new Response();
    }
    public function setCustomerModel(): Customer
    {
        return new Customer();
    }

    public function setCustomerService(): CustomerService
    {
        $connection = new Connection();
        $customerProvider = new CustomerProvider($connection);

        return new CustomerService($customerProvider);
    }

    public function testInsertDataProvider(): array
    {
        return [
            [
                [
                    'name' => "Lorem Ipsum",
                    'ktp' => 1234562401003457,
                    'dateOfBirth' => "2000-01-24",
                    'sex' => "M",
                    'address' => "Jalan Sudirman 1",
                ], 200,
                '{
                    "name": "Lorem Ipsum",
                    "ktp": 1234562401003457,
                    "dateOfBirth": "2000-01-24",
                    "sex": "M",
                    "address": "Jalan Sudirman 1",
                    "id": "37"
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
                '[
                    {
                        "status": "ktp",
                        "message": "Failed Validation",
                        "errors": "ktp must be valid"
                    }
                ]'
            ],
        ];
    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addCustomerRequest = new AddCustomerRequest($this->setCustomerService(), $this->setCustomerModel());
        $responseData = $addCustomerRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addCustomerRequest = new AddCustomerRequest($this->setCustomerService(), $this->setCustomerModel());
        $responseData = $addCustomerRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }

    /** @dataProvider testInsertDataProvider */
    public function testUpdateData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('PUT', '/customer/37', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addCustomerRequest = new UpdateCustomerRequest($this->setCustomerService(), $this->setCustomerModel());
        $responseData = $addCustomerRequest->__invoke($json, $this->setResponse(), ['id' => 37]);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }

    /** @dataProvider testNegativeInsertDataProvider */
    public function testNegativeUpdateData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        //Create Request Body
        $requestBody = $this->createRequest('PUT', '/customer/37', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);

        $addCustomerRequest = new UpdateCustomerRequest($this->setCustomerService(), $this->setCustomerModel());
        $responseData = $addCustomerRequest->__invoke($json, $this->setResponse(), ['id' => 37]);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
        $this->assertEquals(json_decode($expectedResponse, true), json_decode($responseData->getBody(), true));
    }

    public function testGetData()
    {
        //Define Json Body
        $requestBody = $this->createRequest('GET', '/customer', ['Content-Type' => 'application/json']);

        $addCustomerRequest = new GetAllCustomerRequest($this->setCustomerService());
        $responseData = $addCustomerRequest->__invoke($requestBody, $this->setResponse(), ['id' => 3]);

        $this->assertEquals(200, $responseData->getStatusCode());
    }
}
