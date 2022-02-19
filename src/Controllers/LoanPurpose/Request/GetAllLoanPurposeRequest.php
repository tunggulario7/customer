<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Controllers\BaseRequest;
use App\Modules\LoanPurpose\Provider\LoanPurposeProvider;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllLoanPurposeRequest extends BaseRequest
{
    protected LoanPurposeProvider $loanPurposeProvider;
    public function __construct(LoanPurposeProvider $loanPurposeProvider)
    {
        $this->loanPurposeProvider = $loanPurposeProvider;
    }

    public function getResponse(): Response
    {
        $data = $this->loanPurposeProvider->getAll();
        $this->response->getBody()->write(json_encode($data));
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
