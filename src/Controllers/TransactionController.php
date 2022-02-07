<?php

namespace App\Controllers;

use App\Factory\Connection;
use App\Models\TransactionModel;
use App\Services\TransactionDetailService;
use App\Services\TransactionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransactionController
{
    /**
     * @return TransactionService
     */
    public function getTransactionService(): TransactionService
    {
        $connection = new Connection();
        return new TransactionService($connection);
    }

    /**
     * @return TransactionDetailService
     */
    public function getTransactionDetailService(): TransactionDetailService
    {
        $connection = new Connection();
        return new TransactionDetailService($connection);
    }

    public function getAll(Request $request, Response $response): Response
    {
        $data = self::getTransactionService()->getAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function insert(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();
        $transactionValidation = new TransactionModel();
        $validation = $transactionValidation->validate($requestBody);

        if (empty($validation)) {

            //Create Transaction
            $data = [
                'customerId' => $transactionValidation->getCustomerId(),
                'transactionDate' => $transactionValidation->getTransactionDate(),
                'loanPurpose' => $transactionValidation->getLoanPurpose(),
                'loanPeriod' => $transactionValidation->getPeriod(),
                'loanAmount' => $transactionValidation->getLoanAmount()
            ];
            $id = self::getTransactionService()->insert($data);


            //Create Detail Transaction
            $installment = round($transactionValidation->getLoanAmount() / $transactionValidation->getPeriod());
            for ($i = 1; $i <= $transactionValidation->getPeriod(); $i++) {
                $period = $i * 30;
                $datePeriod = '+' . $period . ' days';

                $dueDate = date('Y-m-d', strtotime($datePeriod, strtotime($requestBody['transactionDate'])));

                self::getTransactionDetailService()->insert([
                    'transactionId' => $id,
                    'month' => $i,
                    'dueDate' => $dueDate,
                    'amount' => $installment,
                    'paid' => 0
                ]);
            }

            $returnBody = self::getTransactionService()->getById($id);
            $returnBody['installment'] = self::getTransactionDetailService()->getAllByTransactionId($id);
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
        self::getTransactionService()->delete($id);
        $response->getBody()->write('{
                    "status": "OK",
                    "message": "Delete Success"
                }');
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}