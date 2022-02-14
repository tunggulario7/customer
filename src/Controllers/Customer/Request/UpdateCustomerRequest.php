<?php

namespace App\Controllers\Customer\Request;

use App\Controllers\Customer\Model\Customer;
use App\Services\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCustomerRequest
{
    protected CustomerService $customerService;
    protected Customer $customerModel;
    public function __construct(CustomerService $customerService, Customer $customerModel)
    {
        $this->customerService = $customerService;
        $this->customerModel = $customerModel;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $requestBody = $request->getParsedBody();
        $validation = $this->customerModel->validateUpdate($requestBody, $args);

        if (empty($validation)) {
            $data = [
                'name' => $this->customerModel->getName(),
                'ktp' => $this->customerModel->getKtp(),
                'date_of_birth' => $this->customerModel->getDateOfBirth(),
                'sex' => $this->customerModel->getSex(),
                'address' => $this->customerModel->getAddress(),
            ];
            $id = $this->customerService->update($data, $args);

            unset($data['date_of_birth']);
            $returnBody = $data;
            $returnBody['dateOfBirth'] = $this->customerModel->getDateOfBirth();
            $returnBody['id'] = $id;
            $statusCode = 200;

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
}