<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Controllers\LoanPurpose\Model\LoanPurpose;
use App\Modules\LoanPurpose\Service\LoanPurposeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateLoanPurposeRequest
{
    protected LoanPurposeService $loanPurposeService;
    protected LoanPurpose $loanPurposeModel;
    public function __construct(LoanPurposeService $loanPurposeService, LoanPurpose $loanPurposeModel)
    {
        $this->loanPurposeService = $loanPurposeService;
        $this->loanPurposeModel = $loanPurposeModel;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $validation = $this->loanPurposeModel->validate($request->getParsedBody());

        if (empty($validation)) {
            $data = [
                'name' => $this->loanPurposeModel->getName()
            ];
            $id = $this->loanPurposeService->update($data, $args);

            $returnBody = $data;
            $returnBody['id'] = $id;
            $statusCode = 200;

            $returnBody = json_encode($returnBody);
        } else {
            $returnBody = json_encode($validation);
            $statusCode = 422;
        }

        $response->getBody()->write($returnBody);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

}