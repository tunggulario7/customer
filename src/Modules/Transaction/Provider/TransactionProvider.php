<?php

namespace App\Modules\Transaction\Provider;

use App\Factory\Connection;
use PDO;

class TransactionProvider
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
     * function Get All Transaction Data
     * @return array
     */
    public function getAll(): array
    {
        $sqlQuery = "SELECT transaction_date AS transactionDate, CS.name, CS.ktp, CS.date_of_birth AS dateOfBirth, LP.name AS loanPurpose  FROM transactions TR
    INNER JOIN customers CS ON CS.id = TR.customer_id
    INNER JOIN loan_purpose LP ON LP.id = TR.loan_purpose_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Transaction Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        $sqlQuery = "SELECT transaction_date AS transactionDate, CS.name, CS.ktp, CS.date_of_birth AS dateOfBirth, LP.name AS loanPurpose FROM transactions TR
    INNER JOIN customers CS ON CS.id = TR.customer_id
    INNER JOIN loan_purpose LP ON LP.id = TR.loan_purpose_id WHERE TR.id=:id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("id", $id);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return [];
        }
        return $data;
    }

    /**
     * function Insert Transaction Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO transactions ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Delete Transaction Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM transactions WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}