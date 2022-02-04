<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Factory\Connection;
use App\Models\LoanSettingModel;
use App\Services\LoanSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoanSettingController
{
    /**
     * @return LoanSettingService
     */
    public function getLoanSettingService(): LoanSettingService
    {
        $connection = new Connection();
        return new LoanSettingService($connection);
    }

    public function getAll(Request $request, Response $response): Response
    {
        $data = self::getLoanSettingService()->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function insert(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();
        $loanSettingValidation = new LoanSettingModel();
        $validation = $loanSettingValidation->validate($requestBody);

        if (empty($validation)) {
            $data = [
                'loanPurposeId' => $loanSettingValidation->getLoanPurposeId(),
                'period' => $loanSettingValidation->getPeriod()
            ];
            $id = self::getLoanSettingService()->insert($data);

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
        $loanSettingValidation = new LoanSettingModel();
        $validation = $loanSettingValidation->validate($requestBody);

        if (empty($validation)) {
            $data = [
                'loan_purpose_id' => $loanSettingValidation->getLoanPurposeId(),
                'period' => $loanSettingValidation->getPeriod()
            ];
            $id = self::getLoanSettingService()->update($data, $id);

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
        self::getLoanSettingService()->delete($id);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}