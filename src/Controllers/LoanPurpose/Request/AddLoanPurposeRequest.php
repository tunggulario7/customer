<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Controllers\LoanPurpose\Model\LoanPurpose;
use App\Modules\LoanPurpose\Service\LoanPurposeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddLoanPurposeRequest
{
    protected LoanPurposeService $loanPurposeService;
    protected LoanPurpose $loanPurposeModel;
    public function __construct(LoanPurposeService $loanPurposeService, LoanPurpose $loanPurposeModel)
    {
        $this->loanPurposeService = $loanPurposeService;
        $this->loanPurposeModel = $loanPurposeModel;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $validate = $this->loanPurposeModel->validate($request->getParsedBody());

        if (empty($validate)) {
            $data = [
                'name' => $this->loanPurposeModel->getName(),
            ];
            $id = $this->loanPurposeService->insert($data);

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
