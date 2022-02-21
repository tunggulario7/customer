<?php

namespace Tests\Application\Actions\Customer;

use App\Controllers\Customer\Model\Customer;
use App\Controllers\Customer\Request\AddCustomerRequest;
use App\Controllers\Customer\Request\GetAllCustomerRequest;
use App\Controllers\Customer\Request\UpdateCustomerRequest;
use App\Modules\Customer\Service\CustomerService;
use Slim\Psr7\Response;
use Tests\TestCase;

class CustomerTest extends TestCase
{

//    protected Response $response;
//    protected CustomerService $customerService;
//    protected Customer $customerModel;
//
//    protected function __construct(Response $response, CustomerService $customerService, Customer $customerModel)
//    {
//        $this->response = $response;
//        $this->customerService = $customerService;
//        $this->customerModel = $customerModel;
//    }
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

//    /** @dataProvider testInsertDataProvider */
//    public function testInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
//    {
//        //Create Request Body
//        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
//        $json = $requestBody->withParsedBody($bodyJson);
//
//        var_dump($json);
//
//        $response = new \Slim\Psr7\Response();
//
//        $responseData = new AddCustomerRequest($this->customerService, $this->customerModel);
//        var_dump($responseData);
//        var_dump($responseData->getStatusCode());
//
////        $responseArray = json_decode($responseData->getBody(), true);
//
//        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
////        $this->assertEquals(json_decode($expectedResponse, true), $responseArray);
//    }

    /** @dataProvider testInsertDataProvider */
    public function testInsertData(array $bodyJson, int $expectedStatusCode, string $expectedResponse)
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/customer', ['Content-Type' => 'application/json']);
        $json = $requestBody->withParsedBody($bodyJson);
//        var_dump($requestBody);
//        var_dump($json);

        $response = $app->handle($json);
//        var_dump($response);

        $payload = json_decode($response->getBody(), true);

        $this->assertEquals($expectedResponse, $payload);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
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
