<?php

declare(strict_types=1);

namespace App\Modules\LoanTransaction\Service;

use App\Modules\LoanTransaction\Provider\InstallmentProvider;

class InstallmentService
{
    private InstallmentProvider $installmentProvider;

    public function __construct(InstallmentProvider $installmentProvider)
    {
        $this->installmentProvider = $installmentProvider;
    }

    /**
     * function Get All LInstallment Data
     * @return array
     */
    public function getAllByLoanTransactionId($loanTransactionId): array
    {
        return $this->installmentProvider->getAllByLoanTransactionId($loanTransactionId);
    }

    /**
     * function Get All Installment Data
     * @return array
     */
    public function getAllByLoanTransactionIdNotPaid($loanTransactionId): array
    {
        return $this->installmentProvider->getAllByLoanTransactionIdNotPaid($loanTransactionId);
    }

    /**
     * function Insert Installment Data
     * @param $data
     * @param $loanTransactionId
     * @return string
     */
    public function insert($data, $loanTransactionId): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $field = "loan_transaction_id, month, due_date, amount, underpayment, paid, created_at";
        $value = ":loan_transaction_id, :month, :due_date, :amount, :underpayment, :paid, :created_at";

        for ($i = 0; $i < count($data); $i++) {
            $params = [
                [
                    "field" => ":loan_transaction_id",
                    "value" => $loanTransactionId
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
                    "field" => ":underpayment",
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
            $this->installmentProvider->insert($field, $value, $params);
        }

        return (string) $loanTransactionId;
    }

    /**
     * function Update Installment Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($data, $id): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sql = "UPDATE installments SET ";
        $sqlQuery = '';
        $setField = 'updated_at = :updated_at';

        //Set Field Update
        foreach ($data as $itemId => $value) {
            $setField = $setField . ',' . $itemId . '=' . "'" . $value . "'";
        }

        //Set Query String
        $sqlQuery .= $sql . $setField . ' WHERE id = :id';

        $this->installmentProvider->update($sqlQuery, $dateNow, $id);

        return (string) $id['id'];
    }

    /**
     * function Delete Installment Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->installmentProvider->delete($id);

        return $id['id'];
    }
}
