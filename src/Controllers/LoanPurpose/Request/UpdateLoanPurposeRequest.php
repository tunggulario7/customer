<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Controllers\BaseRequest;
use App\Controllers\LoanPurpose\Model\LoanPurpose;
use App\Modules\LoanPurpose\Provider\LoanPurposeProvider;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateLoanPurposeRequest extends BaseRequest
{
    protected LoanPurposeProvider $loanPurposeProvider;
    protected LoanPurpose $loanPurposeModel;
    public function __construct(LoanPurposeProvider $loanPurposeProvider, LoanPurpose $loanPurposeModel)
    {
        $this->loanPurposeProvider = $loanPurposeProvider;
        $this->loanPurposeModel = $loanPurposeModel;
    }

    public function getResponse(): Response
    {
        $validation = $this->loanPurposeModel->validate($this->request->getParsedBody());

        if (empty($validation)) {
            $data = [
                'name' => $this->loanPurposeModel->getName()
            ];
            $id = $this->loanPurposeProvider->update($data, $this->args);

            $returnBody = $data;
            $returnBody['id'] = $id;
            $statusCode = 200;

            $returnBody = json_encode($returnBody);
        } else {
            $returnBody = json_encode($validation);
            $statusCode = 422;
        }

        $this->response->getBody()->write($returnBody);
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}