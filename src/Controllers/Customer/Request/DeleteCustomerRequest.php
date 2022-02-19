<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Controllers\BaseRequest;
use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteCustomerRequest extends BaseRequest
{
    protected CustomerService $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function getResponse(): Response
    {
        $this->customerService->delete($this->args);
        $this->response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
