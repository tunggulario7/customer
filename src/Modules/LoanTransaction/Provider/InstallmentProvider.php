<?php

declare(strict_types=1);

namespace App\Modules\LoanTransaction\Provider;

use App\Factory\Connection;
use PDO;

class InstallmentProvider
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * function Get All Installment Data
     * @return array
     */
    public function getAllByLoanTransactionId($loanTransactionId): array
    {
        $sqlQuery = "SELECT *  FROM installments WHERE loan_transaction_id =:loan_transaction_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("loan_transaction_id", $loanTransactionId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get All Installment Data
     * @return array
     */
    public function getAllByLoanTransactionIdNotPaid($loanTransactionId): array
    {
        $sqlQuery = "SELECT *  FROM installments WHERE loan_transaction_id =:loan_transaction_id AND paid = 0 ORDER BY month ASC";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("loan_transaction_id", $loanTransactionId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Installment Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO installments ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Update Installment Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($sqlQuery, $dateNow, $id): string
    {
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":updated_at", $dateNow);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return (string) $id['id'];
    }

    /**
     * function Delete Installment Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM installments WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}