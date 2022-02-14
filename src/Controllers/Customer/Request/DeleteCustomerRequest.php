<?php

namespace App\Controllers\Customer\Request;

use App\Services\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteCustomerRequest
{
    protected CustomerService $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->customerService->delete($id);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
