<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Request;

use App\Controllers\BaseRequest;
use App\Controllers\LoanPurpose\Model\LoanPurpose;
use App\Modules\LoanPurpose\Provider\LoanPurposeProvider;
use Psr\Http\Message\ResponseInterface as Response;

class AddLoanPurposeRequest extends BaseRequest
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
        $validate = $this->loanPurposeModel->validate($this->request->getParsedBody());

        if (empty($validate)) {
            $data = [
                'name' => $this->loanPurposeModel->getName(),
            ];
            $id = $this->loanPurposeProvider->insert($data);

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
