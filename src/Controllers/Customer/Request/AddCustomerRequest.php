<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\Customer\Model\Customer;
use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AddCustomerRequest
{
    protected CustomerService $customerService;
    protected Customer $customerModel;
    public function __construct(CustomerService $customerService, Customer $customerModel)
    {
        $this->customerService = $customerService;
        $this->customerModel = $customerModel;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $validate = $this->customerModel->validate($request->getParsedBody());
        if (empty($validate)) {
            $data = [
                'name' => $this->customerModel->getName(),
                'ktp' => $this->customerModel->getKtp(),
                'dateOfBirth' => $this->customerModel->getDateOfBirth(),
                'sex' => $this->customerModel->getSex(),
                'address' => $this->customerModel->getAddress(),
            ];
            $id = $this->customerService->insert($data);

            $returnBody = $data;
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