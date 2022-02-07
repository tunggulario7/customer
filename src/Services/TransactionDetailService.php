<?php

namespace App\Services;

use App\Factory\Connection;
use PDO;

class TransactionDetailService
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
     * function Get All Transaction Detail Data
     * @return array
     */
    public function getAllByTransactionId($transactionId): array
    {
        $sqlQuery = "SELECT *  FROM transaction_details WHERE transaction_id =:transaction_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("transaction_id", $transactionId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Transaction Detail Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sqlQuery = "INSERT INTO transaction_details (transaction_id, month, due_date, amount, paid, created_at) VALUES (:transaction_id, :month, :due_date, :amount, :paid, :created_at)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":transaction_id", $data['transactionId']);
        $query->bindParam(":month", $data['month']);
        $query->bindParam(":due_date", $data['dueDate']);
        $query->bindParam(":amount", $data['amount']);
        $query->bindParam(":paid", $data['paid']);
        $query->bindParam(":created_at", $dateNow);
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Delete Transaction Detail Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM transaction_details WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}