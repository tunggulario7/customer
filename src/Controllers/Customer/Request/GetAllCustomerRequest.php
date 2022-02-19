<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\BaseRequest;
use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllCustomerRequest extends BaseRequest
{
    protected CustomerService $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function getResponse(): Response
    {
        $data = $this->customerService->getAll();
        $this->response->getBody()->write(json_encode($data));
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
