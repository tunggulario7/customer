<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Factory\Connection;
use App\Models\LoanPurposeModel;
use App\Services\LoanPurposeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoanPurposeController
{
    /**
     * @return LoanPurposeService
     */
    public function getLoanPurposeService(): LoanPurposeService
    {
        $connection = new Connection();
        return new LoanPurposeService($connection);
    }

    public function getAll(Request $request, Response $response): Response
    {
        $data = self::getLoanPurposeService()->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function insert(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();
        $loanPurposeValidation = new LoanPurposeModel();
        $validation = $loanPurposeValidation->validate($requestBody);

        if (empty($validation)) {
            $data = [
                'name' => $loanPurposeValidation->getName(),
            ];
            $id = self::getLoanPurposeService()->insert($data);

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

    public function update(Request $request, Response $response, $id): Response
    {
        $requestBody = $request->getParsedBody();
        $loanPurposeValidation = new LoanPurposeModel();
        $validation = $loanPurposeValidation->validate($requestBody);

        if (empty($validation)) {
            $data = [
                'name' => $loanPurposeValidation->getName()
            ];
            $id = self::getLoanPurposeService()->update($data, $id);

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

    public function delete(Request $request, Response $response, $id): Response
    {
        self::getLoanPurposeService()->delete($id);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}