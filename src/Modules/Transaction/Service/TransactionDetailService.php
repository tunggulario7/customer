<?php

declare(strict_types=1);

namespace App\Modules\Transaction\Service;


use App\Modules\Transaction\Provider\TransactionDetailProvider;

class TransactionDetailService
{
    private TransactionDetailProvider $transactionDetailProvider;

    public function __construct(TransactionDetailProvider $transactionDetailProvider)
    {
        $this->transactionDetailProvider = $transactionDetailProvider;
    }

    /**
     * function Get All Transaction Detail Data
     * @return array
     */
    public function getAllByTransactionId($transactionId): array
    {
        return $this->transactionDetailProvider->getAllByTransactionId($transactionId);
    }

    /**
     * function Insert Transaction Detail Data
     * @param $data
     * @return string
     */
    public function insert($data, $transactionId): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $field = "transaction_id, month, due_date, amount, paid, created_at";
        $value = ":transaction_id, :month, :due_date, :amount, :paid, :created_at";

        for ($i = 0; $i < count($data); $i++) {
            $params = [
                [
                    "field" => ":transaction_id",
                    "value" => $transactionId
                ],
                [
                    "field" => ":month",
                    "value" => $data[$i]['period']
                ],
                [
                    "field" => ":due_date",
                    "value" => $data[$i]['dueDate']
                ],
                [
                    "field" => ":amount",
                    "value" => (int) $data[$i]['installment']
                ],
                [
                    "field" => ":paid",
                    "value" => 0
                ],
                [
                    "field" => ":created_at",
                    "value" => $dateNow
                ]
            ];
            $this->transactionDetailProvider->insert($field, $value, $params);
        }

        return (string) $transactionId;
    }

    /**
     * function Delete Transaction Detail Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->transactionDetailProvider->delete($id);

        return $id['id'];
    }

}