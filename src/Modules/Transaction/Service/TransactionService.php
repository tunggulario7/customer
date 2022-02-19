<?php

declare(strict_types=1);

namespace App\Modules\Transaction\Service;

use App\Modules\Transaction\Provider\TransactionProvider;

class TransactionService
{
    private TransactionProvider $transactionProvider;

    public function __construct(TransactionProvider $transactionProvider)
    {
        $this->transactionProvider = $transactionProvider;
    }

    /**
     * function Get All Transaction Data
     * @return array
     */
    public function getAll(): array
    {
        return $this->transactionProvider->getAll();
    }

    /**
     * function Get by ID Transaction Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        return $this->transactionProvider->getById($id);
    }

    /**
     * function Insert Transaction Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $field = "transaction_date, customer_id, loan_purpose_id, loan_period";
        $value = ":transaction_date, :customer_id, :loan_purpose_id, :loan_period";
        $params = [
            [
                "field" => ":transaction_date",
                "value" => $data['transactionDate']
            ],
            [
                "field" => ":customer_id",
                "value" => $data['customerId']
            ],
            [
                "field" => ":loan_purpose_id",
                "value" => $data['loanPurpose']
            ],
            [
                "field" => ":loan_period",
                "value" => $data['loanPeriod']
            ]
        ];

        return $this->transactionProvider->insert($field, $value, $params);
    }

    /**
     * function Delete Transaction Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->transactionProvider->delete($id);

        return $id['id'];
    }
}
