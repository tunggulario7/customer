<?php

declare(strict_types=1);

namespace App\Modules\LoanTransaction\Service;

use App\Modules\LoanTransaction\Provider\LoanTransactionProvider;

class LoanTransactionService
{
    private LoanTransactionProvider $loanTransactionProvider;

    public function __construct(LoanTransactionProvider $loanTransactionProvider)
    {
        $this->loanTransactionProvider = $loanTransactionProvider;
    }

    /**
     * function Get All Loan Transaction Data
     * @return array
     */
    public function getAll(): array
    {
        return $this->loanTransactionProvider->getAll();
    }

    /**
     * function Get by ID Loan Transaction Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        return $this->loanTransactionProvider->getById($id);
    }

    /**
     * function Insert Loan Transaction Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $field = "loan_date, customer_id, loan_purpose_id, loan_period";
        $value = ":loan_date, :customer_id, :loan_purpose_id, :loan_period";
        $params = [
            [
                "field" => ":loan_date",
                "value" => $data['loanDate']
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

        return $this->loanTransactionProvider->insert($field, $value, $params);
    }

    /**
     * function Delete Loan Transaction Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->loanTransactionProvider->delete($id);

        return $id['id'];
    }
}
