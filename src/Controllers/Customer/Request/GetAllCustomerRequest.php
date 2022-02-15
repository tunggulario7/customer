<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Request;

use App\Modules\Customer\Service\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetAllCustomerRequest
{
    protected CustomerService $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $this->customerService->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
