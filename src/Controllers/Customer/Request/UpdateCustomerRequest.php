<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\Customer\Model\Customer;
use App\Modules\Customer\Service\CustomerService;
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
        $validate = $this->customerModel->validateUpdate($requestBody, $args);

        if (empty($validate)) {
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
            $returnBody = json_encode($validate);
            $statusCode = 422;
        }

        $response->getBody()->write($returnBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}