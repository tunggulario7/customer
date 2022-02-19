<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

abstract class BaseRequest
{
    protected Request $request;
    protected ResponseInterface $response;
    protected array $args;
    abstract public function getResponse(): ResponseInterface;

    public function getRequestData(): array
    {
        return $this->request->getParsedBody();
    }

    public function __invoke(Request $request, ResponseInterface $response, $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        return $this->getResponse();
    }

}