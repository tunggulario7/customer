<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Factory\Connection;
use App\Models\CustomerModel;
use App\Services\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerController
{
    /**
     * @return CustomerService
     */
    public function getCustomerService(): CustomerService
    {
        $connection = new Connection();
        return new CustomerService($connection);
    }

    public function getAll(Request $request, Response $response): Response
    {
        $data = self::getCustomerService()->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function insert(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();
        $customerValidation = new CustomerModel();
        $validation = $customerValidation->validate($requestBody);

        //Decalre Variable for Return Response
        $returnBody = '{
                    "status": "OK",
                    "message": "Success"
                }';
        $statusCode = 200;

        $id = 0;

        if (empty($validation)) {
            $data = [
                'name' => $customerValidation->getName(),
                'ktp' => $customerValidation->getKtp(),
                'dateOfBirth' => $customerValidation->getDateOfBirth(),
                'sex' => $customerValidation->getSex(),
                'address' => $customerValidation->getAddress(),
            ];
            $id = self::getCustomerService()->insert($data);

            $returnBody = $data;
            $returnBody['id'] = $id;

            $returnBody = json_encode($returnBody);

        } else {
            $returnBody = json_encode($validation);
            $statusCode = 422;
        }

        $response->getBody()->write($returnBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

    public function delete(Request $request, Response $response, $id): Response
    {
        self::getCustomerService()->delete($id);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
