<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\BaseRequest;
use App\Controllers\Customer\Model\Customer;
use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateCustomerRequest extends BaseRequest
{
    protected CustomerService $customerService;
    protected Customer $customerModel;
    public function __construct(CustomerService $customerService, Customer $customerModel)
    {
        $this->customerService = $customerService;
        $this->customerModel = $customerModel;
    }

    public function getResponse(): Response
    {
        $requestBody = $this->request->getParsedBody();
        $validate = $this->customerModel->validate($requestBody, $this->args['id']);

        if (empty($validate)) {
            $data = [
                'name' => $this->customerModel->getName(),
                'ktp' => $this->customerModel->getKtp(),
                'date_of_birth' => $this->customerModel->getDateOfBirth(),
                'sex' => $this->customerModel->getSex(),
                'address' => $this->customerModel->getAddress(),
            ];
            $id = $this->customerService->update($data, $this->args);

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

        $this->response->getBody()->write($returnBody);
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}