<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\BaseRequest;
use App\Controllers\Customer\Model\Customer;
use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;

class AddCustomerRequest extends BaseRequest
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
        $validate = $this->customerModel->validate($this->getRequestData());
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
        } else {
            $returnBody = $validate;
            $statusCode = 422;
        }

        $this->response->getBody()->write(json_encode($returnBody));
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
